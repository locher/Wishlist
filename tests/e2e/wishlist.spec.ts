import { expect, test } from '@playwright/test'
import { faker } from '@faker-js/faker'

test.beforeEach(() => {
  faker.seed(20260622)
})

test('home shows only connectable parent profiles and blocks child login', async ({
  page,
}) => {
  await page.goto('/')

  await expect(page.getByRole('heading', { name: 'Hello' })).toBeVisible()
  await expect(page.getByText('Alice')).toBeVisible()
  await expect(page.getByText('Bob')).toBeVisible()
  await expect(page.getByText('Charlie')).toBeHidden()
  await expect(page.getByText('Dana')).toBeHidden()

  await page
    .getByTestId('profile-card-Bob')
    .getByRole('button', { name: 'Me connecter' })
    .click()

  await expect(page).toHaveURL(/\/profiles\/\d+$/)
  await expect(
    page.getByRole('heading', { name: 'Mes autres listes modifiables' }),
  ).toBeVisible()
  await expect(page.getByTestId('profile-card-Charlie').first()).toBeVisible()
  await expect(page.getByTestId('profile-card-Dana').first()).toBeVisible()
})

test('guest can browse profiles and reserve then cancel a gift', async ({
  page,
}) => {
  await page.goto('/')
  await page.getByRole('link', { name: 'Me connecter en invité' }).click()
  await page.getByRole('textbox').fill('Camille')
  await page.getByRole('button', { name: 'Valider' }).click()

  await expect(page).toHaveURL(/\/profiles$/)
  await expect(page.getByRole('heading', { name: 'Listes' })).toBeVisible()
  await page
    .getByTestId('profile-card-Alice')
    .getByRole('link', { name: 'Voir la liste' })
    .click()

  const giftCard = page.getByTestId('gift-card-Wooden puzzle')
  await expect(giftCard).toBeVisible()
  await giftCard.getByRole('button', { name: 'Réserver' }).click()
  await expect(giftCard.getByText('Réservé par Camille')).toBeVisible()

  await giftCard.getByRole('button', { name: 'Annuler' }).click()
  await expect(giftCard.getByText('Réservé par Camille')).toBeHidden()
  await expect(giftCard.getByRole('button', { name: 'Réserver' })).toBeVisible()
})

test('owner can create an external list from a profile page', async ({
  page,
}, testInfo) => {
  const listTitle = `${faker.commerce.productName()} ${testInfo.project.name}`

  await page.goto('/')
  await page
    .getByTestId('profile-card-Alice')
    .getByRole('button', { name: 'Me connecter' })
    .click()

  await page.getByRole('button', { name: 'Ajouter un cadeau' }).click()
  await page.getByLabel('Titre').fill(listTitle)
  await page.getByLabel('Lien').fill('https://example.com/jeux-video')
  await page.getByText('Liste externe', { exact: true }).click()
  await page.getByRole('button', { name: 'Ajouter' }).click()

  await expect(
    page.getByRole('heading', { name: 'Mes listes externes' }),
  ).toBeVisible()
  const listCard = page.getByTestId(`gift-card-${listTitle}`)
  await expect(listCard.getByRole('heading', { name: listTitle })).toBeVisible()
  await expect(listCard.getByRole('link', { name: 'Voir' })).toBeVisible()
})

test('visitor can create a parent profile from the legacy-style form', async ({
  page,
}, testInfo) => {
  const profileName = `${faker.person.firstName()} ${testInfo.project.name}`

  await page.goto('/')
  await page.getByRole('link', { name: 'Ajouter un compte' }).click()

  await expect(
    page.getByRole('heading', { name: 'Compte', exact: true }),
  ).toBeVisible()
  await page.getByLabel('Prénom').fill(profileName)
  await page.getByLabel('Haut').fill('M')
  await page.getByLabel('Bas').fill('L')
  await page.getByLabel('Pied').fill('43')
  await page.getByRole('button', { name: 'Ajouter ce compte' }).click()

  await expect(page).toHaveURL(/\/profiles\/\d+$/)
  await expect(page.getByRole('heading', { name: profileName })).toBeVisible()
  await expect(page.getByText('M', { exact: true })).toBeVisible()
  await expect(page.getByText('L', { exact: true })).toBeVisible()
  await expect(page.getByText('43', { exact: true })).toBeVisible()
})
