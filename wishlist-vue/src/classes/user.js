class User {
  constructor(user) {
    this.id = user.id
    this.name = user.name
    this.picture_id = user.picture_id
    this.is_child_account = user.is_child_account
    this.birthday_date = user.birthday_date
    this.size_top = user.size_top
    this.size_bottom = user.size_bottom
    this.size_feet = user.size_feet
    this.date_creation = user.date_creation
    this.date_modification = user.date_modification
  }

  get birthdayDate() {
    if (!this.birthday_date) return null;

    let optionsDate = { year: 'numeric', month: 'long', day: 'numeric' }
    return new Date(this.birthday_date).toLocaleDateString('fr-fr', optionsDate)
  }

  get age() {
    if (!this.birthday_date) return null;

    const date = new Date(this.birthday_date)
    const now = new Date()
    return Math.floor((now - date) / (1000 * 60 * 60 * 24 * 365.25))
  }
}

export default User
