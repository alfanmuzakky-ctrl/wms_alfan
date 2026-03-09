<div class="section-box">
    <div class="section-title">
        Supplier Detail
    </div>

    <form id="editForm"
          data-resource="suppliers"
          data-id="{{ $supplier->id }}">

        @csrf
        @method('PUT')

        <div class="form-grid">

            <div class="form-group">
                <label>Supplier ID</label>
                <input type="text" 
                       value="{{ $supplier->id }}" 
                       readonly 
                       class="readonly-field">
            </div>

            <div class="form-group">
                <label>Supplier Name</label>
                <input type="text" 
                       name="name" 
                       value="{{ $supplier->name }}" 
                       required>
            </div>
            
            <div class="form-group">
                <label>Company Name</label>
                <input type="text" 
                       name="company_name" 
                       value="{{ $supplier->company_name }}" 
                       required>
            </div>
            
            <div class="form-group">
                <label>Phone</label>
                <input type="text" 
                       name="phone" 
                       value="{{ $supplier->phone }}" 
                       placeholder="Contoh: 0812xxxx">
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" 
                       name="email" 
                       value="{{ $supplier->email }}" 
                       placeholder="supplier@email.com">
            </div>
            
            <div class="form-group" style="grid-column: span 2;">
                <label>Full Address</label>
                <textarea name="address" rows="3" required>{{ $supplier->address }}</textarea>
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