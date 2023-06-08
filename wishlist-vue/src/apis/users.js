import User from '@/classes/user'
import { BASE_API } from '@/config/constants'

const ERROR_MESSAGE = 'Erreur lors de la récupération de la liste des utilisateurs'

export async function getUsers(param = {}) {
  // Url parameter management
  // Exemple of parameter : children=0

  const queryString = Object.entries(param)
    .map(([key, value]) => `${key}=${encodeURIComponent(value)}`)
    .join('&')

  try {
    const response = await fetch(BASE_API + '/users?' + queryString)
    const data = await response.json()
    const sortedUsers = data.sort((a, b) => a.name.localeCompare(b.name))

    return sortedUsers.map((user) => new User(user))
  } catch (error) {
    console.error(ERROR_MESSAGE, error)
    throw error
  }
}
