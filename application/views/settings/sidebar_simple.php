<section class="panel">
    <div class="panel-heading">
        <h4 class="panel-title">
            <i class="fas fa-columns"></i> Sidebar Settings
        </h4>
    </div>
    <div class="panel-body">
        <?php
            echo form_open($this->uri->uri_string(), array(
                'method'	=> 'post',
                'class' 	=> 'validate form-horizontal form-bordered'
            ));
        ?>
        <div class="form-group">
            <label class="col-md-2 control-label">Sidebar Color</label>
            <div class="col-md-8">
                <select name="sidebar_color" class="form-control">
                    <option value="default">Default White</option>
                    <option value="blue">Blue</option>
                    <option value="green">Green</option>
                    <option value="purple">Purple</option>
                    <option value="red">Red</option>
                    <option value="dark">Dark</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-md-2 control-label">Sidebar Text Color</label>
            <div class="col-md-8">
                <select name="sidebar_text_color" class="form-control">
                    <option value="light">Light Text</option>
                    <option value="dark">Dark Text</option>
                </select>
            </div>
        </div>
        
        <footer class="panel-footer">
            <div class="row">
                <div class="col-md-2 col-sm-offset-3">
                    <button type="submit" class="btn btn btn-default btn-block" name="submit" value="sidebar">
                        <i class="fas fa-plus-circle"></i> Save
                    </button>
                </div>
            </div>
        </footer>
        <?php echo form_close(); ?>
    </div>
</section>