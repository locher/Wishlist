import User from '@/classes/user'
import { BASE_API } from '@/config/constants'
import Gift from '@/classes/gift'

const ERROR_MESSAGE = 'Erreur lors de la récupération de la liste des cadeaux'

export async function getGiftsPerUserId(id) {
  try {
    const response = await fetch(BASE_API + `/gifts/user/${id}`, {
      method: 'GET'
    })
    const data = await response.json()
    return data.map((gift) => new Gift(gift))
  } catch (error) {
    console.error(ERROR_MESSAGE, error)
    throw error
  }
}

export async function deleteGift(id) {
  try {

    const response = await fetch(BASE_API + `/gifts/${id}`, {
      method: 'DELETE'
    })

    if (!response.ok) return false

    const data = await response.json()
    return data?.affectedRows !== 0;


  } catch (error) {
    console.error(ERROR_MESSAGE, error)
    throw error
  }
}

export async function insertGift(gift) {
  try{
    const response = await fetch(BASE_API + `/gifts`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(gift)
    })
    .then(response => response.json())
    .then(data => {
      console.log('Cadeau ajouté avec succès :', data);
    })

  } catch(error){
    console.error(ERROR_MESSAGE, error)
    throw error
  }
}