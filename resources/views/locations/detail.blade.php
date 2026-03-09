<div class="section-box">
    <div class="section-title">
        Location Detail
    </div>

    <form id="editForm"
          data-resource="locations"
          data-id="{{ $location->id }}">

        @csrf
        @method('PUT')

        <div class="form-grid">

            <div class="form-group">
                <label>ID Location</label>
                <input type="text"
                       value="{{ $location->id }}"
                       readonly
                       class="readonly-field">
            </div>

            <div class="form-group">
                <label>Zone Group</label>
                <input type="text"
                       name="zone_group"
                       value="{{ $location->zone_group }}"
                       placeholder="Contoh: AREA-A / RACK / FLOOR">
            </div>

            <div class="form-group">
                <label>Location Category</label>
                <input type="text"
                       name="location_category"
                       value="{{ $location->location_category }}"
                       placeholder="Contoh: Storage / Picking / Buffer">
            </div>

            <div class="form-group">
                <label>Location Attribute</label>
                <select name="location_attribute" required>

                    <option value="Active"
                        {{ $location->location_attribute == 'Active' ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="Quarantine"
                        {{ $location->location_attribute == 'Quarantine' ? 'selected' : '' }}>
                        Quarantine
                    </option>

                    <option value="Non-Active"
                        {{ $location->location_attribute == 'Non-Active' ? 'selected' : '' }}>
                        Non-Active
                    </option>

                    <option value="Staging"
                        {{ $location->location_attribute == 'Staging' ? 'selected' : '' }}>
                        Staging
                    </option>

                </select>
            </div>

        </div>

        <br>

        <div class="form-actions">
            <button type="submit" class="section-button">
                Save
            </button>
        </div>

    </form>
</div>