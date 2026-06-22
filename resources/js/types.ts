export type ProfileSummary = {
  id: number
  name: string
  isChild: boolean
  avatar: number
  avatarUrl: string
  birthday: string | null
  displayBirthday: string | null
  displayAge: string | null
  sizeTop: string | null
  sizeBottom: string | null
  sizeFeet: string | null
  childrenCount: number | null
  parentIds?: number[]
}

export type GiftSummary = {
  id: number
  title: string
  description: string | null
  link: string | null
  isList: boolean
  isReserved: boolean
  reservedByProfileId: number | null
  reservedByGuestName: string | null
  reservedByCurrentSession: boolean
  reservedBy: {
    id: number
    name: string
    avatarUrl: string
  } | null
}

export type PageProps<T = Record<string, unknown>> = T & {
  flash: {
    success?: string
    error?: string
  }
  locale: 'fr' | 'en'
  supportedLocales: Array<'fr' | 'en'>
  session: {
    activeProfile: Pick<ProfileSummary, 'id' | 'name' | 'avatar'> | null
    guestName: string | null
  }
}
