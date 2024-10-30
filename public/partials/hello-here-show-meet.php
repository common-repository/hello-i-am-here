<div class="container-fluid">

    <div id="app">
        <template>
            <div v-if="is_loading"><?php _e('Loading', 'hello-here'); ?></div>
            <div v-else>
                <div class="meetreunion-section">
                    <div v-show="!show_meet">
                        <form class="meetreunion-form">
                            <div class="form-group">
                                <label for="display_name"><?php _e('Name', 'hello-here'); ?></label>
                                <input v-model="display_name" type="text" id="meetreunion-display_name" name="display_name">
                                <small id="displayNameHelp" class="form-text text-muted">
                                    <?php _e("Insert your name (optional).", "hello-here"); ?>
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="title"><?php _e('Code', 'hello-here'); ?></label>
                                <input v-model="code" type="text" id="meetreunion-code" name="code">
                                <small id="codeHelp" class="form-text text-muted">
                                    <?php _e("Insert your meeting code", "hello-here"); ?>
                                </small>
                            </div>

                            <button @click.prevent="createJitsiMeet" class="btn btn-primary">
                                <?php _e("Connect", "hello-here"); ?>
                            </button>
                        </form>
                        <div class="error-message" v-show="error">
                            {{ error_message }}
                        </div>
                    </div>
                </div>
                <hr>
                <div v-show="show_mobile_info">
                    <?php _e("From a mobile device, you'll need to install the Jitsi App", 'hello-here'); ?>
                    <br>
                    <?php _e('Link generated. Click Go! to connect.', 'hello-here'); ?>
                    <a style="display: block;margin-top: 10px;" class="button" :href="mobile_connect_info">
                        <?php _e('Go!', 'hello-here'); ?>
                    </a>
                </div>
                <div v-show="show_meet" class="meetreunion-section" id="meetreunion">

                </div>
            </div>
        </template>
    </div>

</div>
