
import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)
import Index from '../views/project/index'
import projectDashboard from '../views/projectDashboard/index'
import projectBoard from '../views/projectDashboard/board'
import addProject from '../views/project/edit'


let routes = [

    {
        path: '/projects',
        component: Index
    },
    {
        path: '/project/create',
        component: addProject,
        name : 'project-create'
    },
    // {
    //     path: '/project/:uuid/edit',
    //     component: require('./views/project/edit')
    // },
    // {
    //     path: '/project/:uuid',
    //     component: require('./views/project/show')
    // },
    // {
    //     path: '/report/project/summary',
    //     component: require('./views/project/project-summary')
    // },
    {
        path: '/project-dashboard/:projectId',
        component: projectDashboard,
        name : 'project-dashboard'

    },
    {
        path: '/project-board/:projectId',
        component: projectBoard,
        name : 'project-board'
    }
];

const router = new VueRouter({
    mode:'history',
    routes
});


export default router;
