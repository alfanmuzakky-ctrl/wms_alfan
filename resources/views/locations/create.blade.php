<div class="section-box">

    <div class="section-title">
        Location Detail
    </div>

<form id="addForm">
    @csrf

    <div class="form-grid">

        <div class="form-group">
            <label>ID Location</label>
            <input type="text" name="id">
        </div>

        <div class="form-group">
            <label>Zone Group</label>
            <input type="text" name="zone_group">
        </div>

        <div class="form-group">
            <label>Location Category</label>
            <input type="text" name="location_category">
        </div>

        <div class="form-group">
            <label>Location Attribute</label>
            <select name="location_attribute">
                <option value="Active">Active</option>
                <option value="Quarantine">Quarantine</option>
                <option value="Non-Active">Non-Active</option>
                <option value="Staging">Staging</option>
            </select>
        </div>

    </div>

    <br>

    <button type="submit" class="section-button">
        Add Location
    </button>

</form>

</div>