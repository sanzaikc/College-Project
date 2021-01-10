export default function auth ({ next, store }){
    if(!store.getters.loggedIn){
        return next({
            name: 'login'
        })
    }

    return next()
}
