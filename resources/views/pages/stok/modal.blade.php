<div class="modal fade" role="dialog" id="createModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span id="label-modal"></span> Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="saveData" autocomplete="off">
                <div class="modal-body">
                    <input type="hidden" id="id">
                    <div class="form-group">
                        <label for="tanggal" class="form-label">Tanggal<span class="text-danger">*</span"></label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal">
                        <small class="invalid-feedback" id="errortanggal"></small>
                    </div>
                    <div class="form-group">
                        <label for="produk_id" class="form-label">Produk<span class="text-danger">*</span></label>
                        <select name="produk_id" id="produk_id" class="form-control">
                        </select>
                        <small class="invalid-feedback" id="errorproduk_id"></small>
                    </div>
                    <div class="form-group">
                        <label for="tipe" class="form-label">Tipe<span class="text-danger">*</span></label>
                        <select name="tipe" id="tipe" class="form-control">
                            <option value=""> -- Pilih Tipe --</option>
                            <option value="masuk">Masuk</option>
                            <option value="keluar">Keluar</option>
                        </select>
                        <small class="invalid-feedback" id="errortipe"></small>
                    </div>

                    <div class="form-group">
                        <label for="stok" class="form-label">Stok <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="stok" name="stok">
                        <small class="invalid-feedback" id="errorstok"></small>
                    </div>
                    <div class="form-group">
                        <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="keterangan" name="keterangan">
                        <small class="invalid-feedback" id="errorketerangan"></small>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
