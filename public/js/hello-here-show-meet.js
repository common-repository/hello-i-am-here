var app = new Vue( {
    el  : '#app',
    data: {
        is_loading: false,
        code: '',
        show_meet: false,
        connecting: false,
        room_name: '',
        // display_name: 'Operator',
        default_language: 'en',
        apiUrl: ajax_var.url,
        domain: '',
        display_name: ajax_var.user_name,
        nonce: ajax_var.nonce,
        connected: false,
        jitsiApi: '',
        error: false,
        error_message: '',
        msg: {
            invalid_code: ajax_var.msg_invalid_code
        },
        browser: navigator.userAgent,
        show_mobile_info: false,
        mobile_connect_info: '',
        is_mobile: false
    },
    mounted(){
        this.is_mobile = this.isMobile()
    },
    methods:{
        isMobile: function(){
            const match = window.matchMedia('(pointer:coarse)');
            return (match && match.matches);
        },
        createJitsiMeet: function(event){
            this.resetError()
            let endpoint = this.apiUrl
            this.connecting = true
            // get room info with axios
            let data = new FormData()
            data.append('action', 'meetreunion_get_meetreunion')
            data.append('code', this.code)
            data.append('nonce', this.nonce)
            let vm = this
            axios.post(endpoint, data)
                .then(function(response){
                    if(response.data.meet == null){
                        vm.showError(vm.msg.invalid_code)
                    }else{
                        vm.showJitsiMeet(response.data)
                    }
                })
            this.connecting = false
        },
        showJitsiMeet: function(data){
            if(!this.isMobile()) {
                this.room_name = data.meet.meeting_room
                this.domain = data.meet.domain
                this.connectJitsiMeet()
                this.show_meet = true
            }else{
                this.mobile_connect_info = 'https://'+data.meet.domain+'/'+data.meet.meeting_room
                this.show_mobile_info = true
            }
        },
        connectJitsiMeet(){
            const domain = this.domain
            const options = {
                roomName: this.room_name,
                width: '100%',
                height: '100%',
                parentNode: document.querySelector('#meetreunion'),
                userInfo: {
                    displayName: this.display_name
                },
                configOverwrite: {
                    defaultLanguage: this.default_language,
                    recordingService: {
                        enabled: false
                    },
                    fileRecordingsServiceEnabled: false,
                    localRecording: {
                        disable: true
                    },
                    analytics: {
                        disabled: true,
                    },
                    prejoinConfig: {
                        enabled: true
                    }
                },
            };
            this.jitsiApi = new JitsiMeetExternalAPI(domain, options)
            this.jitsiApi.on('readyToClose', () => {
                this.userLeft()
            })
        },
        userLeft(){
            this.show_meet = false
            this.jitsiApi.dispose()
        },
        showError(message){
            this.error_message = message
            this.error = true
        },
        resetError(){
            this.error = false
            this.error_message = ''
        }
    }
})
