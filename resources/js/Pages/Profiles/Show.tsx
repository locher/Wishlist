import { Head, Link, router, useForm } from '@inertiajs/react'
import { Gift, Save } from 'lucide-react'
import { FormEvent, useState } from 'react'
import { AppLayout } from '@/Layouts/AppLayout'
import { ProfileCard } from '@/Components/ProfileCard'
import { Button } from '@/Components/ui/button'
import { Card, CardContent, CardTitle } from '@/Components/ui/card'
import { Checkbox } from '@/Components/ui/checkbox'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/Components/ui/dialog'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Textarea } from '@/Components/ui/textarea'
import { useI18n } from '@/i18n'
import { GiftSummary, PageProps, ProfileSummary } from '@/types'

type Permissions = {
  owner: boolean
  parent: boolean
  guest: boolean
  canManage: boolean
}

type ShowProps = PageProps<{
  profile: ProfileSummary
  children: ProfileSummary[]
  otherProfiles: ProfileSummary[]
  gifts: GiftSummary[]
  lists: GiftSummary[]
  permissions: Permissions
}>

type GiftFormData = {
  title: string
  description: string
  link: string
  is_list: boolean
}

export default function ProfileShow({
  profile,
  children,
  otherProfiles,
  gifts,
  lists,
  permissions,
}: ShowProps) {
  const { t } = useI18n()
  const [editingGift, setEditingGift] = useState<GiftSummary | null>(null)
  const [giftDialogOpen, setGiftDialogOpen] = useState(false)
  const giftForm = useForm<GiftFormData>({
    title: '',
    description: '',
    link: '',
    is_list: false,
  })

  function openEdit(gift: GiftSummary) {
    setEditingGift(gift)
    setGiftDialogOpen(true)
    giftForm.setData({
      title: gift.title,
      description: gift.description ?? '',
      link: gift.link ?? '',
      is_list: gift.isList,
    })
  }

  function resetGiftForm() {
    setEditingGift(null)
    setGiftDialogOpen(false)
    giftForm.setData({ title: '', description: '', link: '', is_list: false })
    giftForm.clearErrors()
  }

  function submitGift(event: FormEvent<HTMLFormElement>) {
    event.preventDefault()

    if (editingGift) {
      giftForm.put(`/profiles/${profile.id}/gifts/${editingGift.id}`, {
        onSuccess: resetGiftForm,
      })
      return
    }

    giftForm.post(`/profiles/${profile.id}/gifts`, {
      onSuccess: resetGiftForm,
    })
  }

  return (
    <AppLayout title={profile.name} bare>
      <Head title={profile.name} />

      <section className="flex flex-col gap-[70px]">
        <div className="inline-flex max-w-full flex-wrap items-center gap-8 text-primary">
          <div className="relative flex size-[100px] shrink-0 items-center justify-center">
            <div className="kdo-avatar-blob" aria-hidden="true">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 114.73 131.54"
              >
                <path d="M101.72,14.09c11.6,13,12.9,34.8,13,56.8s-1,44-12.6,53.8-33.6,7.4-51.6,3.2-31.7-9.9-40.1-19.7S-.88,84.59.22,72s6.5-24.1,14.9-37.1,19.8-27.5,36.5-32.7S90.12,1.09,101.72,14.09Z" />
              </svg>
            </div>
            <img
              src={profile.avatarUrl}
              alt=""
              className="relative z-10 max-h-[92px] max-w-[110px] object-contain"
            />
          </div>
          <div className="min-w-0 flex-1">
            <h1 className="kdo-title text-[clamp(4rem,9vw,8rem)]">
              {profile.name}
            </h1>
            <div className="mt-4 inline-flex flex-wrap items-center gap-x-10 gap-y-4 text-sm">
              <ProfileDetails profile={profile} />
              {permissions.canManage ? (
                <Button asChild variant="outline">
                  <Link href={`/profiles/${profile.id}/edit`}>
                    {t('profiles.editInfo')}
                  </Link>
                </Button>
              ) : null}
            </div>
          </div>
        </div>

        {children.length > 0 && permissions.owner ? (
          <ProfileLinks
            title={t('profiles.manageableChildren')}
            profiles={children}
            listLabel={t('profiles.accessList')}
          />
        ) : null}

        <div className="flex flex-col gap-[70px]">
          {lists.length ? (
            <section className="flex flex-col gap-8">
              <h2 className="kdo-section-title text-[2.5rem]">
                {t('profiles.externalLists')}
              </h2>
              <div className="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                {lists.map((gift) => (
                  <GiftCard
                    key={gift.id}
                    gift={gift}
                    profile={profile}
                    permissions={permissions}
                    onEdit={() => openEdit(gift)}
                  />
                ))}
              </div>
            </section>
          ) : null}

          <section className="flex flex-col gap-8">
            <h2 className="kdo-section-title text-[2.5rem]">
              {t('profiles.gifts')}
            </h2>
            {gifts.length ? (
              <div className="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                {gifts.map((gift) => (
                  <GiftCard
                    key={gift.id}
                    gift={gift}
                    profile={profile}
                    permissions={permissions}
                    onEdit={() => openEdit(gift)}
                  />
                ))}
              </div>
            ) : (
              <Card>
                <CardContent className="flex flex-col items-center gap-3 py-12 text-center text-muted-foreground">
                  <Gift />
                  <p>{t('profiles.emptyGifts')}</p>
                </CardContent>
              </Card>
            )}
          </section>
        </div>
      </section>

      {permissions.canManage ? (
        <Dialog
          open={giftDialogOpen}
          onOpenChange={(open) => {
            setGiftDialogOpen(open)
            if (!open) resetGiftForm()
          }}
        >
          <DialogTrigger asChild>
            <Button
              className="fixed bottom-8 right-8 z-30 size-16 rounded-full p-0 text-4xl shadow-[0_0_24px_rgba(32,40,89,0.35)]"
              aria-label={t('gift.addLabel')}
            >
              +
            </Button>
          </DialogTrigger>
          <DialogContent className="max-w-3xl border-0 bg-primary text-primary-foreground">
            <DialogHeader>
              <DialogTitle className="kdo-title text-[clamp(3rem,7vw,6rem)] text-primary-foreground">
                {editingGift ? t('gift.editHeading') : t('gift.addHeading')}
              </DialogTitle>
              <DialogDescription className="text-primary-foreground/80">
                {t('gift.help')}
              </DialogDescription>
            </DialogHeader>
            <GiftForm
              form={giftForm}
              onSubmit={submitGift}
              editingGift={editingGift}
              onCancel={resetGiftForm}
              inverted
            />
          </DialogContent>
        </Dialog>
      ) : null}

      {otherProfiles.length ? (
        <div className="kdo-primary-band -mx-4 mt-8 px-4 py-16 sm:-mx-8 sm:px-8">
          {otherProfiles.length ? (
            <ProfileLinks
              title={t('profiles.lists')}
              profiles={otherProfiles}
              inverted
            />
          ) : null}
        </div>
      ) : null}
    </AppLayout>
  )
}

function ProfileDetails({ profile }: { profile: ProfileSummary }) {
  return (
    <div className="flex flex-wrap items-center gap-x-8 gap-y-3">
      {profile.displayBirthday ? (
        <div className="inline-flex items-center gap-2">
          <img src="/images/svg-prod/baby.svg" alt="" className="h-6 w-6" />
          <span>{profile.displayBirthday}</span>
          {profile.displayAge ? (
            <span className="text-xs">{profile.displayAge}</span>
          ) : null}
        </div>
      ) : null}
      {profile.sizeTop ? (
        <SizeInfo icon="tshirt" value={profile.sizeTop} />
      ) : null}
      {profile.sizeBottom ? (
        <SizeInfo icon="pant" value={profile.sizeBottom} />
      ) : null}
      {profile.sizeFeet ? (
        <SizeInfo icon="shoe" value={profile.sizeFeet} />
      ) : null}
    </div>
  )
}

function SizeInfo({ icon, value }: { icon: string; value: string }) {
  return (
    <div className="inline-flex items-center gap-2">
      <img src={`/images/svg-prod/${icon}.svg`} alt="" className="h-6 w-6" />
      <span>{value}</span>
    </div>
  )
}

function GiftForm({
  form,
  onSubmit,
  editingGift,
  onCancel,
  inverted = false,
}: {
  form: ReturnType<typeof useForm<GiftFormData>>
  onSubmit: (event: FormEvent<HTMLFormElement>) => void
  editingGift: GiftSummary | null
  onCancel: () => void
  inverted?: boolean
}) {
  const { t } = useI18n()

  return (
    <form onSubmit={onSubmit} className="grid gap-4 sm:grid-cols-2">
      <div className="flex flex-col gap-2 sm:col-span-2">
        <Label
          htmlFor="title"
          className={inverted ? 'text-primary-foreground' : undefined}
        >
          {t('gift.title')}
        </Label>
        <Input
          id="title"
          value={form.data.title}
          onChange={(event) => form.setData('title', event.target.value)}
          aria-invalid={Boolean(form.errors.title)}
        />
        {form.errors.title ? (
          <p className="text-sm text-destructive">{form.errors.title}</p>
        ) : null}
      </div>
      <div className="flex flex-col gap-2 sm:col-span-2">
        <Label
          htmlFor="description"
          className={inverted ? 'text-primary-foreground' : undefined}
        >
          {t('gift.description')}
        </Label>
        <Textarea
          id="description"
          value={form.data.description}
          onChange={(event) => form.setData('description', event.target.value)}
        />
      </div>
      <div className="flex flex-col gap-2">
        <Label
          htmlFor="link"
          className={inverted ? 'text-primary-foreground' : undefined}
        >
          {t('gift.link')}
        </Label>
        <Input
          id="link"
          type="url"
          value={form.data.link}
          onChange={(event) => form.setData('link', event.target.value)}
        />
      </div>
      <label
        className={`flex items-center gap-3 rounded-md border p-3 ${inverted ? 'border-primary-foreground/40' : ''}`}
      >
        <Checkbox
          checked={form.data.is_list}
          onCheckedChange={(checked) =>
            form.setData('is_list', checked === true)
          }
        />
        <span className="text-sm font-medium">{t('gift.externalList')}</span>
      </label>
      <div className="flex flex-wrap gap-2 sm:col-span-2">
        <Button type="submit" disabled={form.processing}>
          <Save data-icon="inline-start" />
          {editingGift ? t('gift.update') : t('gift.add')}
        </Button>
        {editingGift ? (
          <Button type="button" variant="outline" onClick={onCancel}>
            {t('gift.cancel')}
          </Button>
        ) : null}
      </div>
    </form>
  )
}

function GiftCard({
  gift,
  profile,
  permissions,
  onEdit,
}: {
  gift: GiftSummary
  profile: ProfileSummary
  permissions: Permissions
  onEdit: () => void
}) {
  const { t } = useI18n()

  return (
    <Card
      data-testid={`gift-card-${gift.title}`}
      className={
        gift.isList
          ? 'border-2 border-primary bg-transparent text-primary shadow-none'
          : 'border-2 border-primary bg-primary text-primary-foreground'
      }
    >
      <CardContent className="flex min-h-[96px] flex-col justify-between gap-3 p-4">
        <div className="flex items-start justify-between gap-3">
          <div className="min-w-0">
            <CardTitle
              className={
                gift.isList
                  ? 'text-base font-bold leading-tight text-primary'
                  : 'text-base font-bold leading-tight text-primary-foreground'
              }
            >
              {gift.title}
            </CardTitle>
            {gift.description ? (
              <p
                className={
                  gift.isList
                    ? 'mt-2 text-sm text-primary/70'
                    : 'mt-2 text-sm text-primary-foreground/75'
                }
              >
                {gift.description}
              </p>
            ) : null}
          </div>
          {gift.link ? (
            <Button
              asChild
              variant="outline"
              size="sm"
              className={
                gift.isList
                  ? 'shrink-0 border-secondary text-secondary'
                  : 'shrink-0 border-secondary text-secondary hover:bg-secondary hover:text-primary'
              }
            >
              <a href={gift.link} target="_blank" rel="noreferrer">
                {t('gift.view')}
              </a>
            </Button>
          ) : null}
        </div>
        <div className="flex flex-wrap gap-2">
          {gift.isReserved ? (
            <span
              className={
                gift.isList
                  ? 'text-sm text-primary/70'
                  : 'text-sm text-primary-foreground/75'
              }
            >
              {t('gift.reserved')}
            </span>
          ) : null}
          {!permissions.canManage && !gift.isList && !gift.isReserved ? (
            <Button
              variant="outline"
              size="sm"
              className="border-primary-foreground text-primary-foreground hover:bg-primary-foreground hover:text-primary"
              onClick={() =>
                router.post(
                  `/profiles/${profile.id}/gifts/${gift.id}/reservation`,
                )
              }
            >
              {t('gift.reserve')}
            </Button>
          ) : null}
          {(gift.reservedByCurrentSession || permissions.canManage) &&
          gift.isReserved ? (
            <Button
              variant="outline"
              size="sm"
              onClick={() =>
                router.delete(
                  `/profiles/${profile.id}/gifts/${gift.id}/reservation`,
                )
              }
            >
              {t('gift.cancel')}
            </Button>
          ) : null}
          {permissions.canManage ? (
            <>
              <Button
                variant="outline"
                size="sm"
                className={
                  gift.isList
                    ? undefined
                    : 'border-primary-foreground text-primary-foreground hover:bg-primary-foreground hover:text-primary'
                }
                onClick={onEdit}
              >
                {t('gift.edit')}
              </Button>
              <Dialog>
                <DialogTrigger asChild>
                  <Button variant="destructive" size="sm">
                    {t('gift.delete')}
                  </Button>
                </DialogTrigger>
                <DialogContent>
                  <DialogHeader>
                    <DialogTitle>
                      {t('gift.deleteTitle', { title: gift.title })}
                    </DialogTitle>
                    <DialogDescription>
                      {t('gift.deleteDescription')}
                    </DialogDescription>
                  </DialogHeader>
                  <DialogFooter>
                    <Button variant="outline">{t('gift.cancel')}</Button>
                    <Button
                      variant="destructive"
                      onClick={() =>
                        router.delete(
                          `/profiles/${profile.id}/gifts/${gift.id}`,
                        )
                      }
                    >
                      {t('gift.delete')}
                    </Button>
                  </DialogFooter>
                </DialogContent>
              </Dialog>
            </>
          ) : null}
        </div>
        {gift.reservedBy ? (
          <p
            className={
              gift.isList
                ? 'text-sm text-primary/70'
                : 'text-sm text-primary-foreground/75'
            }
          >
            {t('gift.reservedBy', { name: gift.reservedBy.name })}
          </p>
        ) : null}
        {gift.reservedByGuestName ? (
          <p
            className={
              gift.isList
                ? 'text-sm text-primary/70'
                : 'text-sm text-primary-foreground/75'
            }
          >
            {t('gift.reservedBy', { name: gift.reservedByGuestName })}
          </p>
        ) : null}
      </CardContent>
    </Card>
  )
}

function ProfileLinks({
  title,
  profiles,
  inverted = false,
  listLabel,
}: {
  title: string
  profiles: ProfileSummary[]
  inverted?: boolean
  listLabel?: string
}) {
  const { t } = useI18n()
  const resolvedListLabel = listLabel ?? t('profiles.viewList')

  return (
    <section className="flex flex-col gap-8">
      <div>
        {inverted ? (
          <>
            <h2 className="kdo-title text-[clamp(4rem,9vw,8rem)] text-primary-foreground">
              {title}
            </h2>
            <p className="font-mono text-2xl font-bold leading-none text-primary-foreground">
              {t('profiles.viewLists')}
            </p>
          </>
        ) : (
          <h2 className="kdo-section-title text-[2.5rem]">{title}</h2>
        )}
      </div>
      <div className="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
        {profiles.map((profile) => (
          <ProfileCard
            key={profile.id}
            profile={profile}
            list
            listLabel={resolvedListLabel}
          />
        ))}
      </div>
    </section>
  )
}
