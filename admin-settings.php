<?php
/**
 * Admin Settings Page
 *
 * @package ModernPreloaders
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Handle form submission
if (isset($_POST['submit']) && wp_verify_nonce($_POST['mpreloader_nonce'], 'mpreloader_settings')) {
    $enabled = isset($_POST['mpreloader_enabled']) ? '1' : '0';
    $selected = sanitize_text_field($_POST['mpreloader_selected']);
    
    update_option('mpreloader_enabled', $enabled);
    update_option('mpreloader_selected', $selected);
    
    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Settings saved successfully!', 'modern-preloaders') . '</p></div>';
}

$enabled = get_option('mpreloader_enabled', '0');
$selected = get_option('mpreloader_selected', '1');
?>

<div class="wrap">
    <h1><?php echo esc_html__('Modern Preloaders Settings', 'modern-preloaders'); ?></h1>
    
    <form method="post" action="">
        <?php wp_nonce_field('mpreloader_settings', 'mpreloader_nonce'); ?>
        
        <table class="form-table">
            <tr>
                <th scope="row"><?php echo esc_html__('Enable Preloader', 'modern-preloaders'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="mpreloader_enabled" value="1" <?php checked($enabled, '1'); ?> />
                        <?php echo esc_html__('Enable preloader site-wide', 'modern-preloaders'); ?>
                    </label>
                    <p class="description"><?php echo esc_html__('When enabled, the selected preloader will appear on all pages until they are fully loaded.', 'modern-preloaders'); ?></p>
                </td>
            </tr>
        </table>
        
        <h2><?php echo esc_html__('Choose Your Preloader', 'modern-preloaders'); ?></h2>
        <p><?php echo esc_html__('Select one of the 25 modern preloaders below:', 'modern-preloaders'); ?></p>
        
        <div class="mpreloader-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; margin: 20px 0;">
            <?php for ($i = 1; $i <= 25; $i++) : ?>
                <div class="mpreloader-preview-item" style="border: 2px solid #ddd; border-radius: 8px; padding: 20px; text-align: center; cursor: pointer; transition: all 0.3s ease; <?php echo ($selected == $i) ? 'border-color: #0073aa; box-shadow: 0 0 10px rgba(0,115,170,0.3);' : ''; ?>" data-loader="<?php echo esc_attr($i); ?>">
                    
                    <div class="mpreloader-preview" style="height: 80px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; background: #f9f9f9; border-radius: 4px;">
                        <?php
                        $loader_instance = new ModernPreloaders();
                        echo wp_kses_post($loader_instance->get_loader_html($i));
                        ?>
                    </div>
                    
                    <label style="display: block; cursor: pointer;">
                        <input type="radio" name="mpreloader_selected" value="<?php echo esc_attr($i); ?>" <?php checked($selected, $i); ?> style="margin-right: 8px;" />
                        <?php echo sprintf(esc_html__('Loader %d', 'modern-preloaders'), $i); ?>
                    </label>
                </div>
            <?php endfor; ?>
        </div>
        
        <?php submit_button(); ?>
    </form>
</div>

<style>
.mpreloader-preview-item:hover {
    border-color: #0073aa !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.mpreloader-preview-item.selected {
    border-color: #0073aa !important;
    box-shadow: 0 0 10px rgba(0,115,170,0.3) !important;
}

.mpreloader-preview-item label {
    font-weight: 500;
    color: #333;
}

.mpreloader-preview-item input[type="radio"] {
    transform: scale(1.2);
}
</style>
