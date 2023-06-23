import { BASE_API } from '@/config/constants'
import Gift from '@/classes/Gift'
import List from '@/classes/List'

const ERROR_MESSAGE = 'Erreur lors de la récupération de la liste des cadeaux'

export async function getItems(userID, type) {
  try {
    const response = await fetch(BASE_API + `/items/${type}/user/${userID}`, {
      method: 'GET'
    })
    const data = await response.json()

    if (type === 'gift') {
      return data.map((i) => new Gift(i))
    } else if (type === 'list') {
      return data.map((i) => new List(i))
    }
  } catch (error) {
    console.error(ERROR_MESSAGE, error)
    throw error
  }
}

export async function deleteItem(itemID) {
  try {
    const response = await fetch(BASE_API + `/items/${itemID}`, {
      method: 'DELETE'
    })

    if (!response.ok) return false

    const data = await response.json()
    return data?.affectedRows !== 0
  } catch (error) {
    console.error(ERROR_MESSAGE, error)
    throw error
  }
}

export function insertItem(item) {
  return new Promise(async (resolve, reject) => {
    try {
      const response = await fetch(BASE_API + `/items`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(item)
      })

      const data = await response.json()
      console.log('Item ajouté avec succès :', data)
      resolve(true)
    } catch (error) {
      console.error(ERROR_MESSAGE, error)
      reject(error)
    }
  })
}
