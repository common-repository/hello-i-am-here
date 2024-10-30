var app = new Vue({
    el: '#app',
    data: {
        jitsiid: '',
        jitsipass: '',
        apiUrl: ajax_var.url,
        code: '',
        title: '',
        is_scheduled: false,
        scheduled_date: '',
        domain_default: '',
        domain: '',
        slug: '',
        nonce: '',
        action: '',
        is_loading: true,
        created_message: '',
        created_message_status: false,
        meets: [],
        custom_domain: false
    },
    created() {
        if(ajax_var.domain){
            this.domain = ajax_var.domain
        }else{
            this.domain = this.domain_default
        }
        this.slug = ajax_var.slug
        this.nonce = ajax_var.nonce
        this.action = ajax_var.action

        this.getGoMeets()

        this.is_loading = false;
    },
    mounted(){
        if(this.domain) {
            this.custom_domain = true
        }
    },
    computed: {
        show_date_picker: function(){
            return this.is_scheduled
        },

    },
    methods: {
        isRecent: function(code){
            return this.code == code
        },
        createJitsiMeet: function(event){
            this.created_message = ''
            this.created_message_status = false
            this.generateConnectionID()
            this.generatePass()
            this.generateCodeWeb()

            this.saveJitsiMeet()
        },
        generateConnectionID: function(){
            this.jitsiid = this.slug+'_'+Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15)
        },
        generatePass: function(){
            this.jitsipass = Math.random().toString(36).substring(2, 15)
        },
        generateCodeWeb: function(){
            let d = new Date()
            this.code = d.getDate() + '' + d.getMonth() + '' +  Math.random().toString().substr(2,5)
        },
        setDomainDefault: function(){
            this.domain = this.domain_default
        },
        saveJitsiMeet(){
            let endpoint = this.apiUrl
            let data = new FormData()
            data.append('action', this.action)
            data.append('jitsiid', this.jitsiid)
            data.append('jitsipass', this.jitsipass)
            data.append('code', this.code)
            data.append('title', this.title)
            data.append('is_scheduled', this.is_scheduled)
            data.append('scheduled_date', this.scheduled_date)
            data.append('domain', this.domain)
            data.append('custom_domain', this.custom_domain)
            // nonce from wp_localize_script
            data.append('nonce', this.nonce)
            let vm = this
            axios.post(endpoint, data)
            .then(function(response) {
                if(response.data.status == 1) {
                    vm.title = ''
                    vm.scheduled_date = ''
                    vm.is_scheduled = false
                    // vm.setDomainDefault()
                    vm.created_message_status = true
                    vm.getGoMeets()
                }
                vm.created_message = response.data.message
            })
                .catch(error => {
                })
        },
        getGoMeets:function(){
            let endpoint = this.apiUrl
            let vm = this
            let data = new FormData()
            data.append('action', 'meetreunion_get_meetreunions')
            // nonce from wp_localize_script
            data.append('nonce', this.nonce)
            axios.post(endpoint, data)
                .then(function(response){
                    vm.meets = response.data.meets
                })
        },
        deleteGoMeet: function(id, code){
            let tr = this.$refs[code]
            tr[0].className += 'table-warning'
            let endpoint = this.apiUrl
            let vm = this
            let data = new FormData()
            data.append('action', 'meetreunion_delete_meet_reunion')
            // nonce from wp_localize_script
            data.append('nonce', this.nonce)
            data.append('id', id)

            axios.post(endpoint, data)
                .then(function(response){
                    vm.meets = response.data.meets
                })
        }
    }
})

jQuery(document).ready(function($){
    let flatpickr_config = {
        enableTime: true
    }
    $('#scheduled_date').flatpickr(
        flatpickr_config
    )
})
