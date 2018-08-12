<!-- Modal -->
<div class="modal" id="myModal" tabindex="-1">
    <form class="form-horizontal" id="frmSite">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Form Add/Edit</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="siteId" class="control-label col-md-3">Site Id</label>
                        <div class="col-md-6">
                            <input type="text" id="siteId" name="siteId" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="siteName" class="control-label col-md-3">Site Name</label>
                        <div class="col-md-6">
                            <input type="text" id="siteName" name="siteName" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="regional" class="control-label col-md-3">Regional</label>
                        <div class="col-md-6">
                           <select id="regional" name="regional" class="form-control" required>
                               <option value=""></option>
                           </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="area" class="control-label col-md-3">Area</label>
                        <div class="col-md-6">
                            <select id="area" name="area" class="form-control" required>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="map" class="control-label col-md-3">Map</label>
                        <div class="col-md-9">
                            <div id="map" style="width: 100%;height: 200px;">

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="longitude" class="control-label col-md-3"></label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="longitude" name="longitude" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="latitude" name="latitude" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address" class="control-label col-md-3">Address</label>
                        <div class="col-md-8">
                            <textarea class="form-control" id="address" name="address"></textarea>
                        </div>
                    </div>
                    <input type="hidden" id="id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </form>
</div>



