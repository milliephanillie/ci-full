<?php

namespace ConcreteIron\Includes;

class RapidMemberSince {
    public function _construct() {
        $this->boot();
    }

    public function boot() {
//        add_action('show_user_profile', [$this, 'display_member_since_field']);
//        add_action('edit_user_profile', [$this, 'display_member_since_field']);
    }

    public function display_member_since_field($user) {
        // Get the custom field value
        $member_since_date = get_user_meta($user->ID, 'member_since', true);
        ?>
        <h3>Membership Information</h3>
        <table class="form-table">
            <tr>
                <th><label for="member_since">Member Since</label></th>
                <td>
                    <input type="text" name="member_since" id="member_since" value="<?php echo esc_attr($member_since_date); ?>" class="regular-text" readonly />
                </td>
            </tr>
        </table>
        <?php
    }
}