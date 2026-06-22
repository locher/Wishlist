import { Head, useForm } from '@inertiajs/react'
import { FormEvent } from 'react'
import { AppLayout } from '@/Layouts/AppLayout'
import { Button } from '@/Components/ui/button'
import { useI18n } from '@/i18n'

export default function Guest() {
  const form = useForm({ guest_name: '' })
  const { t } = useI18n()

  function submit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault()
    form.post('/session/guest')
  }

  return (
    <AppLayout title={t('guest.title')} bare>
      <Head title={t('guest.title')} />
      <section className="kdo-guest-page">
        <div className="kdo-guest-content">
          <h1 className="kdo-title kdo-guest-title">{t('guest.heading')}</h1>
          <form onSubmit={submit} className="kdo-guest-form">
            <input
              name="guest_name"
              aria-label={t('guest.name')}
              autoFocus
              value={form.data.guest_name}
              onChange={(event) =>
                form.setData('guest_name', event.target.value)
              }
              aria-invalid={Boolean(form.errors.guest_name)}
              required
            />
            <Button type="submit" disabled={form.processing}>
              {t('guest.submit')}
            </Button>
          </form>
          {form.errors.guest_name ? (
            <p className="kdo-guest-error">{form.errors.guest_name}</p>
          ) : null}
        </div>
      </section>
    </AppLayout>
  )
}
