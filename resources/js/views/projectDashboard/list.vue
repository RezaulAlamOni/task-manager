<template>
    <div>
        <div class="row page-titles">
            <div class="col-md-12 col-12 align-self-center">
                <!--                <h3 class="text-themecolor m-b-0 m-t-0">{{projects.name}}</h3>-->
                <div class="btn-group">
                    <h3 class="text-themecolor ">{{projects.name}}
                    </h3>
                    <p class="dropdown-toggle dropdown-toggle-split" data-toggle="dropdown"></p>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">Add list</a>
                        <a class="dropdown-item" href="#">all list</a>
                    </div>
                </div>

                <ul class="nav" style="border-bottom: 1px solid #cedcc4">
                    <li class="nav-item">
                        <a class="nav-link" href="#">{{trans('list.name')}} <i
                                class="i-btn x20 task-complete icon-circle-o"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">{{trans('board.name')}} <i class="tree-toggle i-btn x30"></i> </a>
                    </li>

                </ul>
            </div>
            <div class="col-md-12">
                <div class="col-8 offset-2">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <a>
                                <i class="fas fa-caret-right" data-toggle="collapse" data-target="#One"></i>
<!--                                <i class="fas fa-caret-down"></i>-->
                                <input type="text" class="input_style" value="One" readonly>
                            </a>
                            <a class="float-right">One</a>
                            <ul id="One" class="collapse list-group list-group-flush">
                                <li class="list-group-item">One - 1</li>
                                <li class="list-group-item">
                                    <a data-toggle="collapse" data-target="#One1">One 1</a>
                                    <ul id="One1" class="collapse list-group list-group-flush">
                                        <li class="list-group-item">One - 1.1</li>
                                        <li class="list-group-item">One - 2.2</li>
                                        <li class="list-group-item">One - 3.3</li>
                                    </ul>
                                </li>
                                <li class="list-group-item">One - 3</li>
                            </ul>
                        </li>
                        <li class="list-group-item">
                            <a data-toggle="collapse" data-target="#two">
                                <i class="fas fa-caret-right"></i>
<!--                                <i class="fas fa-caret-down"></i>-->
                                Two</a>
                            <ul id="two" class="collapse list-group list-group-flush">
                                <li class="list-group-item">Two - 1</li>
                                <li class="list-group-item">Two - 2</li>
                                <li class="list-group-item">Two - 3</li>
                            </ul>
                        </li>
                        <li class="list-group-item">
                            <a data-toggle="collapse" data-target="#two2">
                                <i class="fas fa-caret-right"></i>
<!--                                <i class="fas fa-caret-down"></i>-->
                                Three</a>
                            <ul id="two2" class="collapse list-group list-group-flush">
                                <li class="list-group-item">Three - 1</li>
                                <li class="list-group-item">Three - 2</li>
                                <li class="list-group-item">Three - 3</li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    .input_style {
        border: none;
    }
    .input_style:focus {
        width: 80%;
        border: none;
        /*background: #E6F2D2;*/
    }
</style>

<script>
    import draggableHelper from 'draggable-helper';

    export default {
        components: {th: draggableHelper},
        data() {
            return {
                projects: {
                    id: null,
                    name: null,
                    description: null,
                },
                text : "U  akjsd asd alsdfn al",
                tree4data: [
                    {text: 'node 1' , editable:true},
                    {text: 'node 2'},
                    {text: 'node 3 undraggable', draggable: false},
                    {text: 'node 4'},
                    {text: 'node 4 undroppable', droppable: false},
                    {
                        text: 'node 5', children: [
                            {text: 'node 1'},
                            {
                                text: 'node 2', children: [
                                    {text: 'node 3'},
                                    {text: 'node 4'},
                                ]
                            },
                            {
                                text: 'node 2 undroppable', droppable: false, children: [
                                    {text: 'node 3'},
                                    {text: 'node 4'},
                                ]
                            },
                            {
                                text: 'node 2', children: [
                                    {text: 'node 3'},
                                    {text: 'node 4 undroppable', droppable: false},
                                ]
                            },
                            {text: 'node 3'},
                            {text: 'node 4'},
                            {text: 'node 3'},
                            {text: 'node 4'},
                            {text: 'node 3'},
                            {text: 'node 4'},
                            {text: 'node 3'},
                            {text: 'node 4'},
                        ]
                    },
                ],
            }
        },
        mounted() {
            this.projectId = this.$route.params.projectId;
            this.getProjects();
        },
        methods: {
            hasPermission(permission) {
                return helper.hasPermission(permission);
            },
            getProjects() {
                axios.get('/api/project/' + this.projectId)
                    .then(response => response.data)
                    .then(response => {

                        this.projects = response.project;
                        // this.projectForm.description = response.project.description;
                    })
                    .catch(error => {
                        helper.showDataErrorMsg(error);
                        this.$router.push('/projects');
                    });
            },
            confirmDelete(project) {
                return dialog => this.deleteProject(project);
            },
            addChild() {
                this.tree2data[0].children.push({text: 'child'})
            },
            expandAll() {
                th.breadthFirstSearch(this.tree1data, node => {
                    node.open = true
                })
            },
            collapseAll() {
                th.breadthFirstSearch(this.tree1data, node => {
                    node.open = false
                })
            },

        },
        watch: {
            filterProjectForm: {
                handler(val) {
                    this.getProjects();
                },
                deep: true
            }
        }
    }
</script>
