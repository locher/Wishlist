import User from '@/classes/user'
import { BASE_API } from '@/config/constants'
import Gift from '@/classes/gift'

const ERROR_MESSAGE = 'Erreur lors de la récupération de la liste des cadeaux'

export async function getGiftsPerUserId(id) {
  try {
    const response = await fetch(BASE_API + `/gifts/user/${id}`)
    const data = await response.json()
    return data.map((gift) => new Gift(gift))
  } catch (error) {
    console.error(ERROR_MESSAGE, error)
    throw error
  }
}
