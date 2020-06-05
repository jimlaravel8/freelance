'use strict';

import Vue from 'vue'
import VueRouter from 'vue-router'
import NProgress from 'nprogress'
import VueAnalytics from 'vue-analytics'

import MasterLayout from '../views/layouts/Master.vue'
import CleanLayout from '../views/layouts/Clean.vue'
import Home from '../views/Home.vue'

import i18n from '@/plugins/i18n'
import { loadLanguageAsync } from '@/plugins/i18n'

const routes = [
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '',
      name: 'home',
      components: {
        primary: Home
      }
    }],
    meta: {
      auth: null
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/about',
      name: 'about',
      components: {
        primary: () => import(/* webpackChunkName: "x99bb" */ '../views/About.vue'),
      }
    }],
    meta: {
      auth: null
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/terms-and-conditions',
      name: 'terms',
      components: {
        primary: () => import(/* webpackChunkName: "x4b69" */ '../views/legal/Terms.vue'),
      }
    }],
    meta: {
      auth: null
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/privacy-policy',
      name: 'privacy',
      components: {
        primary: () => import(/* webpackChunkName: "xaee4" */ '../views/legal/Privacy.vue'),
      }
    }],
    meta: {
      auth: null
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/faq',
      name: 'faq',
      components: {
        primary: () => import(/* webpackChunkName: "x653d" */ '../views/contact/Faq.vue'),
      }
    }],
    meta: {
      auth: null
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/contact',
      name: 'contact',
      components: {
        primary: () => import(/* webpackChunkName: "x4bf7" */ '../views/contact/Contact.vue'),
      }
    }],
    meta: {
      auth: null
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/login',
      name: 'login',
      components: {
        primary: () => import(/* webpackChunkName: "x80e2" */ '../views/auth/Login.vue'),
      }
    }],
    meta: {
      auth: false
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/register',
      name: 'register',
      components: {
        primary: () => import(/* webpackChunkName: "xd26e" */ '../views/auth/Register.vue'),
      }
    }],
    meta: {
      auth: false
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/register-business',
      name: 'register-business',
      components: {
        primary: () => import(/* webpackChunkName: "x44c9" */ '../views/auth/RegisterBusiness.vue'),
      }
    }],
    meta: {
      auth: false
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/password/reset',
      name: 'password.email',
      components: {
        primary: () => import(/* webpackChunkName: "xad3a" */ '../views/auth/password/Email.vue'),
      }
    }],
    meta: {
      auth: false
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/password/reset/:token',
      name: 'password.reset',
      components: {
        primary: () => import(/* webpackChunkName: "x13b1" */ '../views/auth/password/Reset.vue'),
      }
    }],
    meta: {
      auth: false
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/settings/profile',
      name: 'settings.profile',
      components: {
        primary: () => import(/* webpackChunkName: "x4d21" */ '../views/settings/Profile.vue'),
      }
    }],
    meta: {
      auth: { roles: [1, 2, 3, 4] }
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/settings/business',
      name: 'settings.business',
      components: {
        primary: () => import(/* webpackChunkName: "x9fa5" */ '../views/settings/Business.vue'),
      }
    }],
    meta: {
      auth: { roles: [3] }
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/settings/business/subscription',
      name: 'settings.business.subscription',
      components: {
        primary: () => import(/* webpackChunkName: "xb806" */ '../views/settings/BusinessSubscription.vue'),
      }
    }],
    meta: {
      auth: { roles: [3] }
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/customer/earn',
      name: 'customer.earn',
      components: {
        primary: () => import(/* webpackChunkName: "xa962" */ '../views/customer/Earn.vue'),
      }
    }],
    meta: {
      auth: { roles: [2] }
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/customer/wallet',
      name: 'customer.wallet',
      components: {
        primary: () => import(/* webpackChunkName: "x4ef4" */ '../views/customer/Wallet.vue'),
      }
    }],
    meta: {
      auth: { roles: [2] }
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/customer/card/:uuid',
      name: 'customer.card',
      components: {
        primary: () => import(/* webpackChunkName: "x8096" */ '../views/customer/Card.vue'),
      }
    }],
    meta: {
      auth: { roles: [2] }
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/business/issue-points',
      name: 'business.issue-points',
      components: {
        primary: () => import(/* webpackChunkName: "x8128" */ '../views/business/IssuePoints.vue'),
      }
    }],
    meta: {
      auth: { roles: [3, 4] }
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/business/redeem-points',
      name: 'business.redeem-points',
      components: {
        primary: () => import(/* webpackChunkName: "x106d" */ '../views/business/RedeemPoints.vue'),
      }
    }],
    meta: {
      auth: { roles: [3, 4] }
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/business/transactions',
      name: 'business.transactions',
      components: {
        primary: () => import(/* webpackChunkName: "x4f1f" */ '../views/business/Transactions.vue'),
      }
    }],
    meta: {
      auth: { roles: [3, 4] }
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/business/staff',
      name: 'business.staff',
      components: {
        primary: () => import(/* webpackChunkName: "x4103" */ '../views/business/Staff.vue'),
      }
    }],
    meta: {
      auth: { roles: [3] }
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/business/rules',
      name: 'business.rules',
      components: {
        primary: () => import(/* webpackChunkName: "x4b7f" */ '../views/business/Rules.vue'),
      }
    }],
    meta: {
      auth: { roles: [3] }
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/admin/dashboard',
      name: 'admin.dashboard',
      components: {
        primary: () => import(/* webpackChunkName: "x434a" */ '../views/admin/Dashboard.vue'),
      }
    }],
    meta: {
      auth: { roles: [1] }
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/admin/users',
      name: 'admin.users',
      components: {
        primary: () => import(/* webpackChunkName: "x975b" */ '../views/admin/Users.vue'),
      }
    }],
    meta: {
      auth: { roles: [1] }
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/404',
      name: '404',
      components: {
        primary: () => import(/* webpackChunkName: "x6f5a" */ '../views/error/404.vue'),
      }
    }],
    meta: {
      auth: null
    }
  },
  {
    path: '/',
    component: MasterLayout,
    children: [{
      path: '/403',
      name: '403',
      components: {
        primary: () => import(/* webpackChunkName: "x40cc" */ '../views/error/403.vue'),
      }
    }],
    meta: {
      auth: true
    }
  },
  {
    path: '/',
    component: CleanLayout,
    children: [{
      path: '/install',
      name: 'install',
      components: {
        primary: () => import(/* webpackChunkName: "x4fc5" */ '../views/install/Install.vue'),
      }
    }],
    meta: {
      auth: null
    }
  },
  { path: '*', redirect: '/404', hidden: true } // Catch unkown routes
]

const router = new VueRouter({
  mode: 'history',
  scrollBehavior: () => ({ y: 0 }),
  routes: routes
})

if (typeof window.init.config.ga !== 'undefined' && window.init.config.ga != '') {
  Vue.use(VueAnalytics, {
    id: window.init.config.ga,
    router
  })
}

NProgress.configure({ showSpinner: false });

// This callback runs before every route change, including on page load.
router.beforeEach((to, from, next) => {
  loadLanguageAsync(i18n.locale).then(() => next())
})

router.beforeResolve((to, from, next) => {
  if (to.name != from.name) {
    NProgress.start()
    document.title = window.init.app.name
  }
  next()
})

router.afterEach((to) => {
  if (to.name) {
    NProgress.done()
  }
})

export default router
