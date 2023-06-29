import { BASE_API } from '@/config/constants'
import Item from '@/classes/Item'

const ERROR_MESSAGE = 'Erreur lors de la récupération de la liste des items'

export async function getItems(userID, type) {
  try {
    const response = await fetch(BASE_API + `/items/${type}/user/${userID}`, {
      method: 'GET'
    })
    const data = await response.json()
    return data.map((i) => new Item(i))
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

export function updateItem(item) {
  return new Promise(async (resolve, reject) => {
    try {
      const response = await fetch(BASE_API + `/items/${item.id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(item)
      })

      const data = await response.json()
      console.log('Item modifié avec succès :', data)
      resolve(true)
    } catch (error) {
      console.error(ERROR_MESSAGE, error)
      reject(error)
    }
  })
}

export function reserveItem(item, user) {
  return new Promise(async (resolve, reject) => {
    try {
      const response = await fetch(BASE_API + `/items/${item.id}/reserve/${user.id}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(item)
      })

      const data = await response.json()
      console.log('Item réservé avec succès :', data)
      resolve(data)
    } catch (error) {
      console.error(ERROR_MESSAGE, error)
      reject(error)
    }
  })
}

export function deleteReservation(item) {
  return new Promise(async (resolve, reject) => {
    try {
      const response = await fetch(BASE_API + `/reservation/${item.reservation_id}`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json'
        }
      })

      const data = await response.json()
      console.log('Réservation supprimée :', data)
      resolve(data)
    } catch (error) {
      console.error(ERROR_MESSAGE, error)
      reject(error)
    }
  })
}
