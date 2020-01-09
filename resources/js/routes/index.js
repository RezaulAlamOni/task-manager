
import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)
import Index from '../views/project/index'
import projectDashboard from '../views/projectDashboard/index'
import projectBoard from '../views/projectDashboard/board'
import addProject from '../views/project/edit'
import Rules from '../views/Rules/index/index'
import Profile from '../views/profile/index'
import ProfileEdit from '../views/profile/edit'
import Notification from '../views/notification/index'
import NotificationSettings from '../views/notification/settings'
import NotificationCreate from '../views/notification/add'


let routes = [

    // { path: '*', redirect: '/projects' },
    {
        path: '/projects',
        component: Index
    },
    {
        path: '/project/create',
        component: addProject,
        name : 'project-create'
    },
    {
        path: '/project/:uuid/edit',
        component: addProject,
        name : 'project-edit'
    },
    {
        path: '/project-dashboard/:projectId',
        component: projectDashboard,
        name : 'project-dashboard'

    },
    {
        path: '/project-board/:projectId',
        component: projectBoard,
        name : 'project-board'
    },
    {
        path: '/project-board/:projectId/rules',
        component: Rules,
        name : 'Rules'
    },
    {
        path: '/profile',
        component: Profile,
        name: 'Profile'
    },
    {
        path: '/profile/edit',
        component: ProfileEdit,
        name: 'ProfileEdit'
    },
    {
        path: '/notification',
        component: Notification,
        name: 'Notification'
    },
    {
        path: '/notification-settings',
        component: NotificationSettings,
        name: 'NotificationSettings'
    },
    {
        path: '/notifications/create',
        component: NotificationCreate,
        name: 'NotificationCreate'
    },
];

const router = new VueRouter({
    mode:'history',
    routes
});


export default router;
