import { spawn, spawnSync } from 'node:child_process'
import { existsSync, rmSync, writeFileSync } from 'node:fs'
import { join } from 'node:path'
import process from 'node:process'

const appUrl = process.env.E2E_BASE_URL ?? 'http://127.0.0.1:18080'
const appKey =
  process.env.APP_KEY ?? 'base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA='
const databasePath =
  process.env.E2E_DB_DATABASE ?? `/tmp/wishlist-e2e-${process.pid}.sqlite`
const useBuiltAssets = process.env.E2E_USE_BUILT_ASSETS === 'true'
const useDocker =
  process.env.E2E_USE_DOCKER ?? (process.env.CI ? 'false' : 'true')
const appEnv = {
  ...process.env,
  APP_NAME: 'Wishlist',
  APP_ENV: 'e2e',
  APP_KEY: appKey,
  APP_DEBUG: 'true',
  APP_URL: appUrl,
  DB_CONNECTION: 'sqlite',
  DB_DATABASE: databasePath,
  SESSION_DRIVER: 'file',
  CACHE_STORE: 'array',
}

const children = []

function run(command, args, options = {}) {
  console.log(`[e2e] ${command} ${args.join(' ')}`)

  const result = spawnSync(command, args, {
    cwd: process.cwd(),
    env: appEnv,
    stdio: 'inherit',
    ...options,
  })

  if (result.status !== 0) {
    process.exit(result.status ?? 1)
  }
}

function start(name, command, args, options = {}) {
  console.log(`[e2e] starting ${name}: ${command} ${args.join(' ')}`)

  const child = spawn(command, args, {
    cwd: process.cwd(),
    env: appEnv,
    stdio: 'inherit',
    ...options,
  })

  children.push(child)

  child.on('exit', (code, signal) => {
    console.error(
      `[e2e] ${name} exited with code ${code ?? 'null'} signal ${signal ?? 'null'}`,
    )
    cleanup()
    process.exit(code ?? 1)
  })
}

function cleanup() {
  for (const child of children) {
    if (!child.killed) {
      child.kill('SIGTERM')
    }
  }

  if (useDocker === 'true') {
    spawnSync('docker', ['rm', '-f', 'wishlist-e2e'], { stdio: 'ignore' })
  }
}

process.on('SIGINT', () => {
  cleanup()
  process.exit(130)
})

process.on('SIGTERM', () => {
  cleanup()
  process.exit(143)
})

if (useDocker === 'true') {
  const envFile = `/tmp/wishlist-e2e-${process.pid}.env`

  writeFileSync(
    envFile,
    Object.entries(appEnv)
      .filter(([key]) =>
        [
          'APP_NAME',
          'APP_ENV',
          'APP_KEY',
          'APP_DEBUG',
          'APP_URL',
          'DB_CONNECTION',
          'DB_DATABASE',
          'SESSION_DRIVER',
          'CACHE_STORE',
        ].includes(key),
      )
      .map(([key, value]) => `${key}=${value}`)
      .join('\n'),
  )

  spawnSync('docker', ['rm', '-f', 'wishlist-e2e'], { stdio: 'ignore' })
  start('laravel', 'docker', [
    'run',
    '--rm',
    '--name',
    'wishlist-e2e',
    '-p',
    '18080:8000',
    '-v',
    `${process.cwd()}:/app`,
    '-v',
    `${envFile}:/app/.env:ro`,
    '-w',
    '/app',
    'composer:2',
    'sh',
    '-lc',
    `rm -f ${databasePath} && touch ${databasePath} && php artisan migrate:fresh --seed --seeder=E2eSeeder && php artisan serve --host=0.0.0.0 --port=8000`,
  ])
} else {
  if (existsSync(databasePath)) {
    rmSync(databasePath)
  }

  writeFileSync(databasePath, '')
  run('php', ['artisan', 'migrate:fresh', '--seed', '--seeder=E2eSeeder'])
  start('laravel', 'php', [
    'artisan',
    'serve',
    '--host=127.0.0.1',
    '--port=18080',
  ])
}

if (!useBuiltAssets) {
  start('vite', 'npm', [
    'run',
    'dev',
    '--',
    '--host',
    '127.0.0.1',
    '--port',
    '5174',
  ])
} else if (!existsSync(join(process.cwd(), 'public/build/manifest.json'))) {
  console.error(
    '[e2e] E2E_USE_BUILT_ASSETS=true but public/build/manifest.json is missing',
  )
  cleanup()
  process.exit(1)
}
