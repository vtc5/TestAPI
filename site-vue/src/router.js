import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/Home.vue'
import Companies from "./views/Companies";
import CompanyEdit from "@/views/CompanyEdit";
import Users from "@/views/Users";
import UserEdit from "@/views/UserEdit";
import Generate from "@/views/Generate";

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
      // route level code-splitting
      // this generates a separate chunk (about.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
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
    }
  ]
})
