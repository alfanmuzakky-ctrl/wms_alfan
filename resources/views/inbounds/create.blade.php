<div class="section-box">
    <div class="section-title">
        Inbound Detail
    </div>

    <form id="addForm">
        @csrf

        <div class="form-grid">

            <div class="form-group">
                <label>ID Inbound</label>
                <input type="text" 
                       name="id" 
                       placeholder="INB-0001" 
                       required>
            </div>

            <div class="form-group">
                <label>Supplier</label>
                <select name="supplier_id" required>
                    <option value="">-- Pilih Supplier --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">
                            {{ $supplier->id }} - {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <br>

        <div class="form-actions">
            <button type="submit" class="section-button">Save Changes</button>
        </div>

    </form>
</div>