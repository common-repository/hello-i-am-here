<div class="container-fluid">

    <div id="app">
        <template>
            <div v-if="is_loading"><?php _e('Loading', 'hello-here'); ?></div>
            <div v-else>
                <div class="donate-button">
                    <span class="donate-text">
                        <?php _e('Make a donation and help the development of the plugin', 'hello-here'); ?>
                    </span>
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                        <input type="hidden" name="cmd" value="_donations" />
                        <input type="hidden" name="business" value="UNKJT2YXBVPMU" />
                        <input type="hidden" name="currency_code" value="EUR" />
                        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
                        <img alt="" border="0" src="https://www.paypal.com/en_ES/i/scr/pixel.gif" width="1" height="1" />
                    </form>
                </div>
                <div class="meetreunion-section">
                    <button class="btn btn-sm btn-info" type="button" data-toggle="collapse" data-target="#disclaimer-text" aria-expanded="false" aria-controls="collapseExample">
			            <?php _e('Disclaimer', 'hello-here'); ?>
                    </button>
                    <div class="collapse" id="disclaimer-text">
                        <br>
                       <p>
                            <?php
                                _e('By using this version of "Hello I am Here", you are aware that your meetings will be hosted on third-party servers. Never share confidential information in your conversations and chats.
If you want to use your own server, you can still use this plugin with your server, but you should hire someone to set it up for you.', 'hello-here');
                            ?>
                       </p>
                    </div>
                </div>
                <div class="meetreunion-section">
                    <button class="btn btn-sm btn-info" type="button" data-toggle="collapse" data-target="#instructions-text" aria-expanded="false" aria-controls="collapseExample">
                        <?php _e('Instructions', 'hello-here'); ?>
                    </button>
                    <div class="collapse" id="instructions-text">
                        <br>
                        <ol>
                            <li><?php _e('Create a new Hello I am here! from this page', 'hello-here');?></li>
                            <li><?php _e('Insert shortcode <code>[show-meet]</code> in any page. Or create a new page and insert there.', 'hello-here'); ?></li>
                            <li><?php _e('Send your client/student/friend/... the meeting code and the page url', 'hello-here'); ?></li>
                            <li><?php _e('You and your client/studen/friend/... put the meeting code in the page', 'hello-here'); ?></li>
                        </ol>
                    </div>
                </div>
                <div class="meetreunion-create meetreunion-section">
                    <h5><?php _e('Create new meeting', 'hello-here'); ?></h5>
                    <form>
                        <div class="form-group">
                            <label for="title"><b><?php _e('Title','hello-here'); ?></b></label>
                            <input v-model="title" type="text" id="title" name="title">
                            <small id="titleHelp" class="form-text text-muted"><?php _e('Set meeting title (optional).', 'hello-here'); ?></small>
                        </div>
                        <div class="form-group">
                            <label for="domain"><b><?php _e('Domain', 'hello-here'); ?></b></label>
                            <br>
                            <span class="chk-custom-domain-wrapper">
                                <label for="custom_domain"><?php _e('I have my own domain', 'hello-here'); ?></label>
                                <input type="checkbox" name="custom_domain" id="custom_domain" v-model="custom_domain"/>
                            </span>
                            <input v-show="custom_domain"  v-model="domain" type="text" id="domain" name="domain">
                            <small id="domainHelp" class="form-text text-muted">
                                <?php _e('If you are not using your own Jitsi servers, let "I have my own domain" unchecked.', 'hello-here');?>
    <!--                            <code @click="setDomainDefault">meet.jit.si (--><?php //_e('Click to set', 'hello-here'); ?><!--)</code>-->
                            </small>
                        </div>
                        <div class="form-group">
                            <input v-model="is_scheduled" type="checkbox" name="is_scheduled" id="is_scheduled">
                            <label class="form-check-label" for="is_scheduled"><?php _e('Schedule it', 'hello-here'); ?></label>
                        </div>
                        <div class="form-group" v-show="show_date_picker">
                            <label for="date"><?php _e('Date/Time', 'hello-here'); ?></label>
                            <input v-model="scheduled_date" type="datetime" id="scheduled_date" name="scheduled_date">
                            <small id="dateHelp" class="form-text text-muted"><?php _e('Select date and time.', 'hello-here'); ?></small>
                        </div>

                        <button @click.prevent="createJitsiMeet" class="btn btn-primary"><?php _e('Create meet!', 'hello-here')?></button>
                        <div class="meetreunion-created-message" v-show="created_message_status">{{created_message}}</div>
                    </form>
                </div>
                <div class="meetreunion-meets meetreunion-section">
                    <h5><?php _e('Created meetings', 'hello-here'); ?></h5>
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th><?php _e('Title', "hello-here");?></th>
                                <th><?php _e('Code', "hello-here");?></th>
                                <th><?php _e('Scheduled Date', "hello-here");?></th>
                                <th><?php _e('Created at', "hello-here");?></th>
                                <th><?php _e('Domain', "hello-here");?></th>
                            </tr>
                        </thead>
                        <tr v-for="meet in meets" :key="meet.id" :class="{'table-info': isRecent(meet.code)}" :ref="meet.code">
                           <td>
                               {{meet.title}}
                               <span class="float-right meetreunion-delete-action" @click="deleteGoMeet(meet.id, meet.code)"><small>
                                       <?php _e('Delete', 'hello-here'); ?>
                                   </small></span>
                           </td>
                           <td>
                               {{meet.code}}
                           </td>
                           <td>
                               {{meet.scheduled_date}}
                           </td>
                           <td>
                               {{meet.created_at}}
                           </td>
                           <td>
                               <small><code>{{meet.domain}}</code></small>
                           </td>
                        </tr>
                    </table>
                </div>
            </div>
        </template>
    </div>

</div>
