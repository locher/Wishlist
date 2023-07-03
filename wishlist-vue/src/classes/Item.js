class Item {
  constructor(item) {
    this.id = item.id
    this.title = item.title
    this.description = item.description
    this.link = item.link
    this.id_user_owner = item.id_user_owner
    this.date_creation = item.date_creation
    this.date_modification = item.date_modification
    this.type = item.type
    this.reservation_id = item.reservation_id
    this.guest_name = item.guest_name
    this.id_user_reservation = item.id_user_reservation
  }

  get isReserved() {
    return !!(this.guest_name || this.id_user_reservation)
  }
}

export default Item
