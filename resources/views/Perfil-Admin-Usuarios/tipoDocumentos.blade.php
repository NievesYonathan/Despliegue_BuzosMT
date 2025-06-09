<x-app-layout>
	<div class="full-box page-header">
		<h3 class="text-left">
			<i class="fas fa-plus fa-fw"></i> &nbsp; GESTIONAR TIPOS DE DOCUMENTOS
		</h3>
	</div>

	<!-- Content -->
	<div class="container-fluid">
		<form action="{{ route('tipoDocumentos.store') }}" method="POST" class="form-neon" autocomplete="off">
			@csrf
			<fieldset>
				<legend><i class="fas fa-user-lock"></i> &nbsp; Registrar Tipos de Documentos</legend>
				<div class="container-fluid">
					<div class="row">
						<div class="col-12 col-md-12">
							<div class="form-group">
								<label for="nombreDoc" class="bmd-label-floating">Ingrese el tipo de documento</label>
								<input type="text" class="form-control" name="tip_doc_descripcion" id="nombreDoc">
							</div>
						</div>
					</div>
				</div>
			</fieldset>
			<p class="text-center" style="margin-top: 40px;">
				<button type="reset" class="btn btn-raised btn-secondary btn-sm">
					<i class="fas fa-paint-roller"></i> &nbsp; LIMPIAR
				</button>
				&nbsp; &nbsp;
				<button type="submit" class="btn btn-raised btn-info btn-sm">
					<i class="far fa-save"></i> &nbsp; GUARDAR
				</button>
			</p>
		</form>

		<br>

		<div class="form-neon mt-20">
			<legend><i class="fa-regular fa-address-book"></i> &nbsp; Lista de Tipos de Documentos</legend>
			<div class="row">
				@foreach ($tipoDocumentos as $tipo)
				<div class="col-12 col-md-6 mb-2">
					<div class="row align-items-center">
						<div class="col-12 col-sm-7">
							<i class="fas fa-check-circle"></i> {{ $tipo['tip_doc_descripcion'] }}
						</div>
						<div class="col-12 col-sm-5 d-flex justify-content-sm-end gap-2 mt-1 mt-sm-0">
							<button data-bs-toggle="modal" data-bs-target="#updateModal{{ $tipo['id_tipo_documento'] }}">
								<i class="fa-regular fa-pen-to-square"></i>
							</button>
							<form class="form-eliminar" action="{{ route('tipo-documentos.delete', $tipo->id_tipo_documento) }}" method="POST">
								@csrf
								@method('DELETE')
								<button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
							</form>
						</div>	
					</div>
				</div>

				<!-- Modal para editar tipos de documento -->
				<div class="modal fade" id="updateModal{{ $tipo['id_tipo_documento'] }}" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<form action="{{ route('tipoDocumentos.update', $tipo['id_tipo_documento']) }}" method="POST">
									@csrf
									@method('PUT')
									<fieldset>
										<legend><i class="far fa-address-card"></i> &nbsp; Editar Tipo de Documento</legend>
										<div class="container-fluid">
											<div class="row">
												<div class="col-12 col-md-12">
													<div class="form-group">
														<label for="nombreDocEdit" class="bmd-label-floating">Nombre</label>
														<input type="text" class="form-control" name="tip_doc_descripcion" id="tip_doc_descripcion" value="{{ $tipo['tip_doc_descripcion'] }}" maxlength="60">
													</div>
												</div>
											</div>
										</div>
									</fieldset>
									<div class="modal-footer">
										<button class="btn btn-success" type="submit">Actualizar</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				@endforeach
			</div>
		</div>
	</div>

    {{-- Mostrar error --}}
    @if ($errors->has('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: '{{ $errors->first('error') }}',
            });
        </script>
    @endif

    {{-- Mostrar éxito --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
            });
        </script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const forms = document.querySelectorAll('.form-eliminar');

            forms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault(); // evita el envío inmediato

                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Esta acción no se puede deshacer",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // solo se envía si se confirma
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>
