<template>
    <div class="index">
<!--        <Navbar :AllNavGet="AllNavItems"-->
<!--                :projectId="$route.params.projectId"-->
<!--                :lists="list"-->
<!--                @getList="showTask"-->
<!--                @getNavBars="getNavbar">-->
<!--        </Navbar>-->


        <Rules
            :project_id="$route.params.projectId"
            :id="rule.id"
            :action_type="rule.action_type"
            @ruleUpdate="RuleUpdate">
        </Rules>
    </div>
</template>

<script>
    import Rules from "../Rules";
    import Navbar from "../../projectDashboard/ProjectNavbar/Navbar";
    export default {
        components : {
            Rules ,Navbar
        },
        props: {
        },

        data(){
            return{
                projectId : null,
                rule : {
                    id:null,
                    action_type : 'rules'
                },
                AllNavItems : null,
                list : {
                    type : 'rules'
                },
            }
        },
        mounted() {
            this.projectId = this.$route.params.projectId;

        },
        methods:{
            RuleUpdate(){

            },
            showTask(data) {
                setTimeout(function () {
                    $('#details').removeClass('details');
                }, 300);
                // $('#loder-hide').fadeIn();
                this.list_id = data.list_id;
                this.nav_id = data.nav_id;
                this.list.nav_id = data.nav_id;
                this.list.name = data.title;
                this.list.id = data.list_id
                this.list.description = data.description;
                this.list.type = data.type;
                this.projectId = this.$route.params.projectId;

                    localStorage.selected_nav = JSON.stringify({
                        list_id: data.list_id,
                        nav_id: data.nav_id,
                        title : data.title,
                        description : this.list.description,
                        project_id: this.projectId,
                        type: data.type
                    });

                this.$router.push({name: 'project-dashboard', params: {projectId: this.projectId, name: ''}});

            },
            getNavbar(){

            }
        }

    };
</script>
