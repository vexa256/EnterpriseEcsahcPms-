<!-- resources/views/mpaIndicators/select-entity.blade.php -->
<div class="col-12">
    <div class="card">
        <!-- Card Header -->
        <div class="card-header">
            <h4 class="card-title">
                {{ $Desc }}
            </h4>
        </div>

        <!-- Card Body -->
        <div class="card-body">
            <!-- Form to select MPA Entity (calls ShowEntityIndicators) -->
            <form action="{{ route('mpaIndicators.ShowEntityIndicators') }}" method="GET">
                @csrf

                <!-- Searchable Auto-Complete Select (using Tabler's data-bs-toggle="select") -->
                <div class="mb-3">
                    <label class="form-label" for="EntityID">Select Entity</label>
                    <select class="form-select" data-bs-toggle="select"
                        data-bs-placeholder="Search or select an option..." id="EntityID" name="EntityID" required>
                        <option value="" disabled selected>Please select...</option>
                        @foreach ($entities as $ent)
                            <option value="{{ $ent->EntityID }}">
                                {{ $ent->Entity }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="mb-3 float-end">
                    <button type="submit" class="btn btn-primary">
                        Manage Indicators
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
