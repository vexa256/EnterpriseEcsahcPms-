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
            <!-- Replace the '#' with your desired form action route -->
            <form action="{{ route('MgtEcsaIndicators') }}" method="GET">
                @csrf

                <!-- Searchable Auto-Complete Select (using Tabler's data-bs-toggle="select") -->
                <div class="mb-3">
                    <label class="form-label" for="StrategicObjectiveID">Strategic Objective</label>
                    <select class="form-select " data-bs-toggle="select"
                        data-bs-placeholder="Search or select an option..." id="StrategicObjectiveID"
                        name="StrategicObjectiveID" required>
                        <option value="" disabled selected>Please select...</option>
                        @foreach ($strategicObjectives as $obj)
                            <option value="{{ $obj->StrategicObjectiveID }}">
                                {{ $obj->SO_Number }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="mb-3 float-end">
                    <button type="submit" class="btn btn-primary">
                        Attach Indicators
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
