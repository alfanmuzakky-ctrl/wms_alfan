<div class="section-box">
    <div class="section-title">
        Customer Detail
    </div>

    <form id="editForm" 
          data-resource="customers" 
          data-id="{{ $customer->id }}">
        
        @csrf
        @method('PUT')

        <div class="form-grid">

            <div class="form-group">
                <label>Customer ID</label>
                <input type="text" value="{{ $customer->id }}" readonly class="input-readonly">
            </div>

            <div class="form-group">
                <label>Customer Name</label>
                <input type="text" name="name" value="{{ $customer->name }}">
            </div>

            <div class="form-group">
                <label>Company Name</label>
                <input type="text" name="company_name" value="{{ $customer->company_name }}">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ $customer->email }}">
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" value="{{ $customer->phone }}">
            </div>

            <div class="form-group">
                <label>Contact Person</label>
                <input type="text" name="contact_person" value="{{ $customer->contact_person }}">
            </div>

            <div class="form-group">
                <label>Contact Phone</label>
                <input type="text" name="contact_phone" value="{{ $customer->contact_phone }}">
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label>Address</label>
                <textarea name="address" rows="3">{{ $customer->address }}</textarea>
            </div>

        </div>

        <br>

        <div class="form-actions">
            <button type="submit" class="section-button">Save</button>
        </div>

    </form>
</div>