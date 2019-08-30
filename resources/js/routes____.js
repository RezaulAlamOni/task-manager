import Vue from 'vue'
import VueRouter from 'vue-router'
import Index from './views/project/index'

let routes____ = [

            {
                path: '/projects',
                component: Index
            },
            // {
            //     path: '/project/create',
            //     component: require('./views/project/index')
            // },
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
            // {
            //     path: '/project-dashboard/:projectId',
            //     component: require('./views/projectDashboard/index'),
            //     name : 'project-dashboard'
            // },
            // {
            //     path: '/project-board/:projectId',
            //     component: require('./views/projectDashboard/board'),
            //     name : 'project-board'
            // }
];

const router = new VueRouter({
    routes: routes____
});


export default router;
