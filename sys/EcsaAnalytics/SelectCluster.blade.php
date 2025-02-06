<div class="container-fluid p-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <h1 class="display-4 text-center mb-5">Select a Cluster</h1>
            <div class="row g-4">
                @if (Auth::user()->AccountRole === 'Admin')
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card card-stacked-hover h-100 bg-azure-lt cursor-pointer cluster-card"
                            data-cluster="All clusters">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                                <div class="avatar avatar-xl mb-3 bg-azure">
                                    <i class="fas fa-globe fa-lg"></i>
                                </div>
                                <h3 class="card-title mb-2">All Clusters</h3>
                                <p class="text-muted text-center">View comprehensive data across all clusters</p>
                            </div>
                        </div>
                    </div>
                @endif

                @foreach ($clusters as $cluster)
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card card-stacked-hover h-100 cursor-pointer cluster-card"
                            data-cluster="{{ $cluster->ClusterID }}">
                            <div class="ribbon ribbon-top ribbon-start bg-primary">
                                {{ $cluster->ClusterID }}
                            </div>
                            <div class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                                <div
                                    class="avatar avatar-xl mb-3 bg-{{ ['blue', 'green', 'red', 'yellow', 'purple'][array_rand(['blue', 'green', 'red', 'yellow', 'purple'])] }}-lt">
                                    <i
                                        class="fas fa-{{ ['chart-line', 'microscope', 'heartbeat', 'pills', 'dna'][array_rand(['chart-line', 'microscope', 'heartbeat', 'pills', 'dna'])] }} fa-lg"></i>
                                </div>
                                <h3 class="card-title mb-2">{{ $cluster->Cluster_Name }}</h3>
                                <p class="text-muted text-center">{{ Str::limit($cluster->Description, 100) }}</p>
                            </div>
                            <div class="card-footer bg-transparent border-0">
                                <div class="row align-items-center g-2">
                                    <div class="col-auto ms-auto">
                                        <div class="avatar-list avatar-list-stacked">
                                            @for ($i = 0; $i < min(rand(2, 5), 5); $i++)
                                                <span
                                                    class="avatar avatar-xs rounded-circle bg-{{ ['blue', 'green', 'red', 'yellow', 'purple'][array_rand(['blue', 'green', 'red', 'yellow', 'purple'])] }}-lt">
                                                    <i class="fas fa-user fa-xs"></i>
                                                </span>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <span class="badge bg-blue-lt">
                                            <i class="fas fa-users me-1"></i>
                                            {{ rand(5, 20) }} members
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<form id="cluster-form" action="{{ route('select-year') }}" method="POST" class="d-none">
    @csrf
    <input type="hidden" name="cluster" id="selected-cluster">
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const clusterCards = document.querySelectorAll('.cluster-card');
        const clusterForm = document.getElementById('cluster-form');
        const selectedClusterInput = document.getElementById('selected-cluster');

        clusterCards.forEach(card => {
            card.addEventListener('click', function() {
                const clusterId = this.dataset.cluster;
                selectedClusterInput.value = clusterId;
                clusterForm.submit();
            });

            card.addEventListener('mouseenter', function() {
                this.classList.add('shadow-lg');
                this.style.transform = 'translateY(-5px) scale(1.02)';
            });

            card.addEventListener('mouseleave', function() {
                this.classList.remove('shadow-lg');
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    });
</script>

<style>
    .card-stacked-hover {
        transition: all 0.3s ease;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .cluster-card:hover .avatar {
        transform: scale(1.1);
        transition: transform 0.3s ease;
    }

    .avatar i {
        opacity: 0.8;
    }

    .cluster-card:hover .avatar i {
        opacity: 1;
    }
</style>
