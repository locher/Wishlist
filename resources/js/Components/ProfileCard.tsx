import { Link, router } from '@inertiajs/react'
import { Gift, LogIn } from 'lucide-react'
import { Badge } from '@/Components/ui/badge'
import { Button } from '@/Components/ui/button'
import { Card, CardContent, CardFooter, CardTitle } from '@/Components/ui/card'
import { useI18n } from '@/i18n'
import { ProfileSummary } from '@/types'

export function ProfileCard({
  profile,
  home = false,
  list = false,
  listLabel,
}: {
  profile: ProfileSummary
  home?: boolean
  list?: boolean
  listLabel?: string
}) {
  const compact = home || list
  const { t } = useI18n()
  const resolvedListLabel = listLabel ?? t('profiles.viewList')

  return (
    <Card
      data-testid={`profile-card-${profile.name}`}
      className={
        compact
          ? 'flex items-center justify-start gap-5 p-4'
          : 'flex items-center gap-4 p-5'
      }
    >
      <div
        className={
          compact
            ? 'relative flex size-[78px] shrink-0 items-center justify-center'
            : 'relative flex size-[75px] shrink-0 items-center justify-center'
        }
      >
        <div className="kdo-avatar-blob" aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 114.73 131.54">
            <path d="M101.72,14.09c11.6,13,12.9,34.8,13,56.8s-1,44-12.6,53.8-33.6,7.4-51.6,3.2-31.7-9.9-40.1-19.7S-.88,84.59.22,72s6.5-24.1,14.9-37.1,19.8-27.5,36.5-32.7S90.12,1.09,101.72,14.09Z" />
          </svg>
        </div>
        <img
          src={profile.avatarUrl}
          alt=""
          className={
            compact
              ? 'relative z-10 max-h-[72px] max-w-[82px] object-contain'
              : 'relative z-10 max-h-[68px] max-w-[78px] object-contain'
          }
        />
      </div>
      <div className="min-w-0 flex-1">
        <CardTitle
          className={
            compact
              ? 'truncate text-[1.7rem] font-normal leading-none text-foreground'
              : 'truncate text-[2.1rem] font-bold leading-none text-primary'
          }
        >
          {profile.name}
        </CardTitle>
        {compact ? (
          <Button
            onClick={
              home
                ? () => router.post(`/session/profile/${profile.id}`)
                : undefined
            }
            asChild={list}
            className="mt-3 min-w-[170px]"
          >
            {list ? (
              <Link href={`/profiles/${profile.id}`}>{resolvedListLabel}</Link>
            ) : (
              t('profiles.login')
            )}
          </Button>
        ) : (
          <>
            <CardContent className="mt-2 p-0 text-sm text-muted-foreground">
              {profile.displayAge ? (
                <p>{profile.displayAge}</p>
              ) : (
                <p>{t('profiles.personalList')}</p>
              )}
            </CardContent>
            <div className="mt-3 flex flex-wrap gap-2">
              <Badge variant={profile.isChild ? 'secondary' : 'default'}>
                {profile.isChild ? t('profiles.child') : t('profiles.parent')}
              </Badge>
              {profile.childrenCount ? (
                <Badge variant="outline">
                  {t('profiles.childrenCount', {
                    count: profile.childrenCount,
                  })}
                </Badge>
              ) : null}
            </div>
          </>
        )}
      </div>
      {!compact ? (
        <CardFooter className="flex shrink-0 flex-col gap-2 p-0">
          <Button
            onClick={() => router.post(`/session/profile/${profile.id}`)}
            size="sm"
          >
            <LogIn data-icon="inline-start" />
            {t('profiles.login')}
          </Button>
          {!compact ? (
            <Button asChild variant="outline" size="sm">
              <Link href={`/profiles/${profile.id}`}>
                <Gift data-icon="inline-start" />
                {t('profiles.view')}
              </Link>
            </Button>
          ) : null}
        </CardFooter>
      ) : null}
    </Card>
  )
}
