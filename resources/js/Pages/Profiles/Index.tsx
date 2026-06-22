import { Head } from '@inertiajs/react'
import { AppLayout } from '@/Layouts/AppLayout'
import { ProfileCard } from '@/Components/ProfileCard'
import { useI18n } from '@/i18n'
import { PageProps, ProfileSummary } from '@/types'

type ProfilesIndexProps = PageProps<{
  profiles: ProfileSummary[]
}>

export default function ProfilesIndex({ profiles }: ProfilesIndexProps) {
  const { t } = useI18n()

  return (
    <AppLayout title={t('profiles.indexTitle')} bare>
      <Head title={t('profiles.lists')} />
      <section className="kdo-primary-band -mx-4 min-h-[86svh] px-4 py-[10vh] sm:-mx-8 sm:px-8">
        <div className="mx-auto max-w-[1120px]">
          <div className="mb-12 text-primary-foreground">
            <h1 className="kdo-title text-[clamp(4rem,9vw,8rem)] text-primary-foreground">
              {t('profiles.lists')}
            </h1>
            <p className="font-mono text-2xl font-bold leading-none">
              {t('profiles.viewLists')}
            </p>
          </div>
          <div className="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            {profiles.map((profile) => (
              <ProfileCard key={profile.id} profile={profile} list />
            ))}
          </div>
        </div>
      </section>
    </AppLayout>
  )
}
