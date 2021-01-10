import Vue from 'vue';
import VueRouter from 'vue-router'
import {routes} from './routes'
import store from '../store/index'

import middlewarePipeline from './middlewarePipeline'

Vue.use(VueRouter);

export const router = new VueRouter({
  mode: 'history',
  routes,
});

// router.beforeEach((to, from, next) => {
//   if (to.matched.some((record) => record.meta.auth)) {
// 		if (!store.getters.loggedIn) {
// 			next({
// 				path: "/login",
// 			});
// 		} else {
// 			next();
// 		}
// 	} else if (to.matched.some((record) => record.meta.guest)) {
// 		if (store.getters.loggedIn) {
// 			if (store.state.auth.currentUser.is_admin)
// 				next({
// 					path: "/admin",
// 				});
// 			else
// 				next({
// 					path: "/host",
// 				});
// 		} else {
// 			next();
// 		}
// 	} else {
// 		next();
// 	}
// })

router.beforeEach((to, from, next) => {
	if (!to.meta.middleware) {
		return next()
	}
	const middleware = to.meta.middleware

	const context = {
		to,
		from,
		next,
		store
	}


	return middleware[0]({
		...context,
		next: middlewarePipeline(context, middleware, 1)
	})

});
