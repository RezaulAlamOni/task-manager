import VueRouter from 'vue-router'

let routes = [

            {
                path: '/project',
                component: require('./views/project/index')
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
    routes
});


export default router;
