export default function guest({ next, store }) {
    if(store.getters.loggedIn){
        if (store.state.auth.currentUser.is_admin)
            next({
                path: "/admin",
        });
        else
            next({
                path: "/host",
		});
    }

    return next()
}
