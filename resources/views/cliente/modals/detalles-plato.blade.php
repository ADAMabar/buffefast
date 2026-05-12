<div class="modal fade" id="modalDetallePlato" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            
            {{-- Imagen grande arriba --}}
            <div class="position-relative" style="height: 250px;">
                <img id="modalPlatoImagen" src="" alt="" 
                     class="w-100 h-100 object-fit-cover">
                
                {{-- Botón cerrar flotante --}}
                <button type="button" class="btn-close btn-close-white position-absolute" 
                        data-bs-dismiss="modal" aria-label="Cerrar"
                        style="top: 15px; right: 15px; background-color: rgba(0,0,0,0.5); padding: 8px; border-radius: 50%;">
                </button>
                
                {{-- Precio badge --}}
                <div class="position-absolute" style="bottom: 15px; right: 15px;">
                    <span id="modalPlatoPrecio" class="badge bg-white text-dark fw-bold fs-5 px-3 py-2 rounded-pill shadow-sm">
                        0.00€
                    </span>
                </div>
            </div>
            
            {{-- Contenido abajo --}}
            <div class="modal-body p-4">
                <h3 id="modalPlatoNombre" class="fw-bold mb-3 text-dark"></h3>
                
                <p id="modalPlatoDescripcion" class="text-muted mb-4" style="line-height: 1.7; font-size: 1rem;">
                </p>
                
                {{-- Botón añadir al carrito --}}
                <form id="modalAddToCartForm" action="" method="POST" class="d-grid">
                    @csrf
                    <input type="hidden" name="plato_id" id="modalPlatoId">
                    <button type="submit" class="btn btn-lg fw-bold rounded-pill"
                            style="background-color: var(--primary-orange); color: white; border: none;">
                        <i class="bi bi-cart-plus me-2"></i>
                        Añadir al carrito
                    </button>
                </form>
            </div>
            
        </div>
    </div>
</div>
 
<style>
.object-fit-cover {
    object-fit: cover;
}
</style>