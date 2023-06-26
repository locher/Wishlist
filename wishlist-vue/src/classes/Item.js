class Item {
  constructor(item) {
    this.id = item.id
    this.title = item.title
    this.description = item.description
    this.link = item.link
    this.id_user = item.id_user
    this.date_creation = item.date_creation
    this.date_modification = item.date_modification
    this.type = item.type
  }

  delete() {
    console.log('deleteItem')
  }

  restore() {
    console.log('restore')
  }

  add() {
    console.log('add')
  }

  update() {
    console.log('update')
  }
}

export default Item
