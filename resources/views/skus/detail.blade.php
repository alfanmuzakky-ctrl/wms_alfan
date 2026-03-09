<div class="section-box">
    <div class="section-title">
        SKU Detail
    </div>

    <form id="editForm"
          data-resource="skus"
          data-id="{{ $sku->id }}">

        @csrf
        @method('PUT')

        <div class="form-grid">

            <div class="form-group">
                <label>ID SKU</label>
                <input type="text"
                       value="{{ $sku->id }}"
                       readonly
                       class="readonly-field">
            </div>

            <div class="form-group">
                <label>Alternative Code</label>
                <input type="text"
                       name="alternative_code"
                       value="{{ $sku->alternative_code }}">
            </div>

            <div class="form-group">
                <label>Product Name</label>
                <input type="text"
                       name="name"
                       value="{{ $sku->name }}"
                       required>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category" required>
                    <option value="Dry" {{ $sku->category == 'Dry' ? 'selected' : '' }}>Dry</option>
                    <option value="Cool" {{ $sku->category == 'Cool' ? 'selected' : '' }}>Cool</option>
                    <option value="Chilled" {{ $sku->category == 'Chilled' ? 'selected' : '' }}>Chilled</option>
                    <option value="Sub-Zero" {{ $sku->category == 'Sub-Zero' ? 'selected' : '' }}>Sub-Zero</option>
                    <option value="Frozen" {{ $sku->category == 'Frozen' ? 'selected' : '' }}>Frozen</option>
                </select>
            </div>

            <div class="form-group">
                <label>UOM</label>
                <select name="uom" required>
                    <option value="Pcs" {{ $sku->uom == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                    <option value="Pack" {{ $sku->uom == 'Pack' ? 'selected' : '' }}>Pack</option>
                    <option value="Carton" {{ $sku->uom == 'Carton' ? 'selected' : '' }}>Carton</option>
                    <option value="Bag" {{ $sku->uom == 'Bag' ? 'selected' : '' }}>Bag</option>
                </select>
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label>Description</label>
                <textarea name="description"
                          rows="4"
                          placeholder="Detail produk...">{{ $sku->description }}</textarea>
            </div>

            <div class="form-group">
                <label>Create Time</label>
                <input type="text"
                       value="{{ $sku->created_at }}"
                       readonly
                       class="readonly-field">
            </div>

            <div class="form-group">
                <label>Edit Time</label>
                <input type="text"
                       value="{{ $sku->updated_at }}"
                       readonly
                       class="readonly-field">
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