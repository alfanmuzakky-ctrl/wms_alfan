<div class="section-box">
    <div class="section-title">
        SKU Detail
    </div>

    <form id="addForm">
        @csrf

        <div class="form-grid">

            <div class="form-group">
                <label>ID SKU</label>
                <input type="text" name="id" placeholder="SKU-001" required>
            </div>

            <div class="form-group">
                <label>Alternative Code</label>
                <input type="text" name="alternative_code" placeholder="Barcode / kode lain">
            </div>

            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category" required>
                    <option value="">Select Category</option>
                    <option value="Dry">Dry</option>
                    <option value="Cool">Cool</option>
                    <option value="Chilled">Chilled</option>
                    <option value="Sub-Zero">Sub-Zero</option>
                    <option value="Frozen">Frozen</option>
                </select>
            </div>

            <div class="form-group">
                <label>UOM</label>
                <select name="uom" required>
                    <option value="">Select Unit</option>
                    <option value="Pcs">Pcs</option>
                    <option value="Pack">Pack</option>
                    <option value="Carton">Carton</option>
                    <option value="Bag">Bag</option>
                </select>
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label>Description</label>
                <textarea name="description" rows="3" placeholder="Tambahkan detail produk di sini..."></textarea>
            </div>

        </div>

        <br>

        <div class="form-actions">
            <button type="submit" class="section-button">Add SKU</button>
        </div>

    </form>
</div>