import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/Home.vue'
import Companies from "./views/Companies";
import CompanyEdit from "@/views/CompanyEdit";
import Users from "@/views/Users";
import UserEdit from "@/views/UserEdit";
import Generate from "@/views/Generate";
import Login from "./views/Login";

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home
    },
    {
      path: '/about',
      name: 'about',
      component: function () {
        return import(/* webpackChunkName: "about" */ './views/About.vue')
      }
    },
    {
      path: '/companies',
      name: 'Companies',
      component: Companies
    },
    {
      path: '/company/:id/:isEdit',
      name: 'CompanyEdit',
      component: CompanyEdit
    },
    {
      path: '/users',
      name: 'Users',
      component: Users
    },
    {
      path: '/user/:id/:isEdit',
      name: 'UserEdit',
      component: UserEdit
    },
    {
      path: '/generate',
      name: 'Generate',
      component: Generate
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
    }
  ]
})
