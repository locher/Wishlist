export function getStorage(key) {
    const value = localStorage.getItem(key) || sessionStorage.getItem(key)
    // Parsez la valeur JSON si elle existe
    return value ? JSON.parse(value) : null
}

export function setStorage(key, value, persistent = true) {
    const stringValue = JSON.stringify(value)
    if (persistent) {
        localStorage.setItem(key, stringValue)
    } else {
        sessionStorage.setItem(key, stringValue)
    }
}

export function deleteStorage(key){
    localStorage.removeItem(key)
}