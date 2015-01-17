<?php
/*
* Clase que contiene operaciones de una matriz
* Fecha de Creación: 30/sept/2005
* Diego Carrera - Kleber Baño
* Guayaquil - Ecuador
*
*/
class matrix {
	//global vars
	var $RowCount;	
	var $ColCount;
	var $ArrayData=array();
	//advanced global vars	
	var $ArrayMedia;
	var $ArrayMatrizCov;

	/**
	 * Contructor de la clase matriz
	 *
	 * @param array $ArrayDataMatriz
	 * @return matrix
	 */
	function matrix($ArrayDataMatriz) {
		$this->set_data($ArrayDataMatriz);
		if (!$this->set_properties_matrix())
		 return false;
	return true;
	}


/******************************************/	
/*FUNCIONES DE BASICAS DE LA CLASE MATRIX */

	/**
	 * Setea los datos que se le da a la matriz al momento de iniciar el objeto
	 *
	 * @param array $ArrayDataMatriz
	 */
	function set_data($ArrayDataMatriz){
		for ($i=0;$i<count($ArrayDataMatriz);$i++){
			$valor = $ArrayDataMatriz[$i];
			if (count($ArrayDataMatriz[$i])==1){
				$this->ArrayData[$i][0] = $ArrayDataMatriz[$i];	
			}
			else
			for ($j=0;$j<count($ArrayDataMatriz[$i]);$j++)
				$this->ArrayData[$i][$j] = $ArrayDataMatriz[$i][$j];	
		}		
	}


	/**
	 * Setee las propiedades de la matriz como son las filas y columnas
	 *
	 * @return unknown
	 */
	function set_properties_matrix(){
		$this->RowCount = count($this->ArrayData );
		$this->ColCount = count($this->ArrayData[0]);
		if ($this->ValidaNumColumnasObjMatriz($this->RowCount,$this->ColCount)){
			return true;
		}
		$this->ColCount=null;
		$this->RowCount=null;
		return false;
	}
	
	/**
	 * Setee el número de filas de la matriz
	 *
	 */
	function set_RowCount(){
		$this->RowCount = count($this->ArrayData[0] );
	}
	
	/**
	 * Setee el número de columna de la matriz
	 *
	 */	
	function set_ColCount(){
		$this->ColCount = count($this->ArrayData);
	}

	/**
	 * Obtiene el número de filas que tiene el objeto matriz
	 *
	 * @return integer
	 */
	function get_RowCount()	{
		return $this->RowCount;
	}

	/**
	 * Obtiene el número de columnas que tiene el objeto matriz
	 *
	 * @return integer
	 */
	function get_ColCount()	{
		return $this->ColCount;
	}
	
	/**
	 * Obtiene el arreglo de datos de la matriz media del objeto matriz
	 *
	 * @return Arraymatriz
	 */
	function getMediaMatrix(){
		$this->MediasMatriz();
		return $this->ArrayMedia;
	}	

	/**
	 * Obtiene el arreglo de datos de la matriz de covarianza
	 *
	 * @param Arraydata $ArrayData
	 * @return ArrayData
	 */
	function getCovarianceMatrix($ArrayData){
		$this->ArrayMatrizCov=$this->CovarianceMatrix($ArrayData);
		return $this->ArrayMatrizCov;
	}
	
	/**
	 * Obtiene el número de filas que tiene un Arreglo de una matriz
	 *
	 * @param ArrayData $ArrayDataMatriz
	 * @return integer
	 */
	function get_RowCount_ArrayDataMatrix($ArrayDataMatriz){
		//echo "la supesta filas es ".count($ArrayDataMatriz);
		//print_r($ArrayDataMatriz);
		return count($ArrayDataMatriz);
	}
	
	/**
	 * Obtiene el número de columnas que tiene un arreglo de una matriz
	 *
	 * @param ArrayData $ArrayDataMatriz
	 * @return integer
	 */
	function get_ColCount_ArrayDataMatrix($ArrayDataMatriz){
		//echo "la supesta columan es ".count($ArrayDataMatriz[0]);
		return count ($ArrayDataMatriz[0]);
	}



/******************************************/	
/*FUNCIONES DE VALIDACIONES DE MATRICES */

	/**
	 * Funcion que valida si dos matrices son iguales, es decir que tengan el mismo numero de N y M
	 *
	 * @param matrix $matrizA
	 * @param matrix $matrizB
	 * @return bool
	 */
	function CheckMatrixDimension($ObjMatrizA, $ObjMatrizB){
		//valida que las matrices sean v&aacute;lidas
		if ($ObjMatrizA->RowCount==$ObjMatrizB->RowCount and $ObjMatrizA->ColCount==$ObjMatrizB->RowCount)
			return true;
		else
			return false;
	}
		

	/**
	 * Funcion que valida que la matriz sea de NxN
	 *
	 * @return bool
	 */
	function CheckMatrix_N_X_N(){
		if ($this->RowCount == $this->ColCount)
			 return true;
			 
	return false;
	}
	
	/**
	 * Valida que el numero de columna de una matriz.. se el mismo en todas sus filas
	 *
	 * @param integer $NumFilas
	 * @param integer $NumColumnas
	 * @return bool
	 */
	function ValidaNumColumnasObjMatriz($NumFilas,$NumColumnas){
		for ($i=0;$i<$NumFilas;$i++){
			$columna = count($this->ArrayData [$i]);
			if ($NumColumnas != $columna)
				return false;
		}
	return true;
	}
	
	/**
	 * Dado un arreglo de datos de una matriz, valida que el número de 
	 * columnas de una matriz.. se el mismo en todas sus filas
	 *
	 * @param ArrayData $ArrayDataMatriz
	 * @param integer $NumFilas
	 * @param integer $NumColumnas
	 * @return unknown
	 */
	function ValidaNumColumnasArrayDataMatriz($ArrayDataMatriz,$NumFilas,$NumColumnas){
		for ($i=0;$i<$NumFilas;$i++){
			$columna = count($ArrayDataMatriz[$i]);
			if ($NumColumnas != $columna)
				return false;
		}
	return true;
	}

	
	

/************************************************/	
/*FUNCIONES DE OPERACIONES BASICAS CON MATRICES */

	/**
	 * Resta de dos matrices
	 * Requisito: tiene que se de iguales valores de NxM
	 *
	 * @param ArrayData $ArrayDataMatriz1
	 * @param ArrayData $ArrayDataMatriz2
	 * @return ArrayData
	 */
	function SubtractMatrix($ArrayDataMatriz1, $ArrayDataMatriz2){
		$Row1 = $this->get_RowCount_ArrayDataMatrix($ArrayDataMatriz1);
		$Row2 = $this->get_RowCount_ArrayDataMatrix($ArrayDataMatriz2);
		$Col1 = $this->get_ColCount_ArrayDataMatrix($ArrayDataMatriz1);
		$Col2 = $this->get_ColCount_ArrayDataMatrix($ArrayDataMatriz2);
		
		for($i=0; $i<$Row1; $i++) {   
			for($j=0; $j<$Col1; $j++){
				$ArrayResta[$i][$j]= $ArrayDataMatriz1[$i][$j]-$ArrayDataMatriz2[$i][$j];
			}
		}
		return $ArrayResta;
		
	}
	
	/**
	 * Suma de dos matrices
	 * Requisito: tiene que se de iguales valores de NxM
	 *
	 * @param ArrayData $ArrayDataMatriz1
	 * @param ArrayData $ArrayDataMatriz2
	 * @return ArrayData
	 */
	function AddMatrix($ArrayDataMatriz1, $ArrayDataMatriz2){
		$Row1 = $this->get_RowCount_ArrayDataMatrix($ArrayDataMatriz1);
		$Row2 = $this->get_RowCount_ArrayDataMatrix($ArrayDataMatriz2);
		$Col1 = $this->get_ColCount_ArrayDataMatrix($ArrayDataMatriz1);
		$Col2 = $this->get_ColCount_ArrayDataMatrix($ArrayDataMatriz2);
		
		for($i=0; $i<$Row1; $i++) {   
			for($j=0; $j<$Col1; $j++){
				$ArrayResta[$i][$j]= $ArrayDataMatriz1[$i][$j]+$ArrayDataMatriz2[$i][$j];
			}
		}
		return $ArrayResta;
		
	}	
	


	/**
	 * Calcula la matriz resultante de multiplicar dos matrices
	 * Requisito: los datos de las matrices A,B, tiene que cumplir que
	 * El # de columnas de A, tienen que se igual a las filas de B.
	 * C(pxq) = A(pxm) * B(mxq)
	 *
	 * @param ArrayData $ArrayDataMatriz1
	 * @param ArrayData $ArrayDataMatriz2
	 * @return ArrayData
	 */
	function MultiplyMatrix($ArrayDataMatriz1, $ArrayDataMatriz2) {
		$Row1 = $this->get_RowCount_ArrayDataMatrix($ArrayDataMatriz1);
		$Col1 = $this->get_ColCount_ArrayDataMatrix($ArrayDataMatriz1);

		$Col2 = $this->get_ColCount_ArrayDataMatrix($ArrayDataMatriz2);		
		$Row2 = $this->get_RowCount_ArrayDataMatrix($ArrayDataMatriz2);

		for($i=0; $i<$Row1; $i++){
			for($j=0; $j<$Col2; $j++){
				$ArrayMultipli[$i][$j]=0; $sum=0;
				for($M=0;$M<$Col1;$M++){
					$ArrayMultipli[$i][$j]  = $ArrayMultipli[$i][$j] + $ArrayDataMatriz1[$i][$M]*$ArrayDataMatriz2[$M][$j];
				}
			}
		}
		return $ArrayMultipli;
	}
		
	/**
	 * Calcula la matriz resultante al dividir una matriz para un escalar
	 *
	 * @param ArrayData $ArrayDataMatriz
	 * @param integer $valor
	 * @return ArrayData
	 */
	function DivideMatrix($ArrayDataMatriz, $valor) {
		$Row = $this->get_RowCount_ArrayDataMatrix($ArrayDataMatriz);
		$Col = $this->get_ColCount_ArrayDataMatrix($ArrayDataMatriz);

		$matriz = array();
		for($i = 0; $i < $Row; $i++) {
			for($j = 0; $j < $Col; $j++) {
				$matriz[$i][$j] = $ArrayDataMatriz[$i][$j] / $valor;
			}
		}
		return $matriz;
	}
	
	/**
	 * Calcula el Determinant de una matriz.
	 * Requisito: Todas filas deben tener el mismo número de columnas.
	 * Requisito: La matriz debe ser de NxN
	 *
	 * @param ArrayData $ArrayDataMatriz
	 * @return integer
	 */
	function Determinant($ArrayDataMatriz) {
		$Row = $this->get_RowCount_ArrayDataMatrix($ArrayDataMatriz);
		$Col = $this->get_ColCount_ArrayDataMatrix($ArrayDataMatriz);
		$det = 0;
		if ($Row == 2 && $Col == 2) {
			$det = $ArrayDataMatriz[0][0] * $ArrayDataMatriz[1][1] - $ArrayDataMatriz[0][1] * $ArrayDataMatriz[1][0];
		} else {
			$matriz = array();
			/* Recorrer las columnas pivotes */
			for($j = 0; $j < $Col; $j++) {
				/* Se crea una sub matriz */
				$matriz = $this->SubMatriz($ArrayDataMatriz, 0, $j);
				if (fmod($j, 2) == 0) {
					$det += $ArrayDataMatriz[0][$j]*$this->Determinant($matriz);
				} else {
					$det -= $ArrayDataMatriz[0][$j]*$this->Determinant($matriz);
				}
			}
		}
		return $det;
	}


	/**
	 * Enter description here...
	 *
	 * @param ArrayData $ArrayDataMatriz
	 * @param integer $pivoteX
	 * @param integer $pivoteY
	 * @return ArrayData
	 */
	function SubMatriz($ArrayDataMatriz, $pivoteX, $pivoteY) {
		//echo "determiando SUBMATRIZ<br>";
		$Row = $this->get_RowCount_ArrayDataMatrix($ArrayDataMatriz);
		$Col = $this->get_ColCount_ArrayDataMatrix($ArrayDataMatriz);
		$matriz = array();
		$p = 0; // indica la fila de la nueva submatriz
		for($i = 0; $i < $Row; $i++) {
			$q = 0; // indica la columna de la nueva submatriz
			if ($pivoteX != $i) {
				for($j = 0; $j < $Col; $j++) {
					if ($pivoteY != $j) {
						$matriz[$p][$q] = $ArrayDataMatriz[$i][$j];
						$q++;
					}
				}
				$p++;
			}
		}
		return $matriz;
	}


	/**
	 * Calcula la matriz Transpose de la matriz dada
	 *
	 * @param ArrayData $ArrayDataMatriz
	 * @return ArrayData
	 */
	function Transpose($ArrayDataMatriz) {
		$Row = $this->get_RowCount_ArrayDataMatrix($ArrayDataMatriz);
		$Col = $this->get_ColCount_ArrayDataMatrix($ArrayDataMatriz);
		$ArrayTranspuesta = array();
		for($i = 0; $i < $Row; $i++) {
			for($j = 0; $j < $Col; $j++) {
				$ArrayTranspuesta[$j][$i] = $ArrayDataMatriz[$i][$j];
			}
		}
		return $ArrayTranspuesta;
		
	}


	/**
	 * Calcula la matriz inversa de la matriz dada
	 *
	 * @param ArrayData $ArrayDataMatriz
	 * @return ArrayData
	 */
	function InverseMatrix($ArrayDataMatriz) {
		$Row = $this->get_RowCount_ArrayDataMatrix($ArrayDataMatriz);
		$Col = $this->get_ColCount_ArrayDataMatrix($ArrayDataMatriz);
		$matriz = array();
		for($i = 0; $i < $Row; $i++) {
			for($j = 0; $j < $Col; $j++) {
				if (fmod($i + $j, 2) == 0) {
					$matriz[$i][$j] = $this->Determinant($this->SubMatriz($ArrayDataMatriz, $i, $j));
				} else {
					$matriz[$i][$j] = -$this->Determinant($this->SubMatriz($ArrayDataMatriz, $i, $j));
				}
			}
		}
		return $this->Transpose($this->DivideMatrix($matriz,$this->Determinant($ArrayDataMatriz)));
	}
	

/**************************************************/	
/*FUNCIONES DE OPERACIONES AVANZADAS CON MATRICES */
	
	/**
	 * 	M = mean(A)  return la media de los valores de una dimension del arreglo
	 * 	If A is a vector, mean(A) returns the mean value of A.
	 * 	If A is a matrix, mean(A) treats the columns of A as vectors, returning a row vector of mean values.
	 * 	A = [1 2 3; 3 3 6; 4 6 8; 4 7 7];
	 * 	mean(A)= [ 3.0000    4.5000    6.000 ]
	 *
	 * @return unknown
	 */
	function MediasMatriz(){
		//encero los valores para el arreglo que va a almacenar las medias y las sumas
		for ($j=0; $j<$this->ColCount; $j++){
			$this->ArrayMedia[$j]=0;
			$suma_media[$j]=0;
		}
		for ($j=0; $j<$this->ColCount; $j++){
			for ($i=0; $i<$this->RowCount; $i++){
				$suma_media[$j]+=$this->ArrayData[$i][$j];
			}
			//echo "suma $i,$j=".$this->ArrayData[$i][$j]."<br>";
			$this->ArrayMedia[$j]=$suma_media[$j]/$this->RowCount;
			$this->ArraySumaMedia[$j]=$suma_media[$j];
		}
	//retorno la matriz con los promedio de cada columna (la matriz de la media)
	return true;
	}
	

		

	/**
	 * COV(X,Y)
	 * Calcula la covarianza entre los vectores x i y
	 * C = CovarianzaVector(x) where x,y  are vectors, 
	 *
	 * @param array $vectorX
	 * @param array $vectorY
	 * @return integer
	 */
	function CovarianceVector ($vectorX, $vectorY){
		$NumFilas = count($vectorX);
		if ($NumFilas != count($vectorY)) return null;
		$covarianza = 0;$sum=0;
		$mean_x = $this->MediaVector($vectorX);
		$mean_y = $this->MediaVector($vectorY);
		for($i = 0; $i < $NumFilas; $i++) {
			$valor = (($vectorX[$i] - $mean_x) * ($vectorY[$i] - $mean_y));
			$sum += $valor;
		}
		$covarianza = $sum / $NumFilas;
		return $covarianza;
	}
	
	
	/**
	 * Calcula la matriz de covarianza de una matriz A
	 * S = CovarianceMatrix(x) where A es una matriz.
	 * Cada fila es una observacion y cada columna es una variable
	 * n = numero de observaciones (# filas)
	 * Cov (A)= A * A'
	*/
	function CovarianceMatrix($ArrayData){
		$transpA = $this->Transpose($ArrayData);
		$MatrizCov =  $this->MultiplyMatrix($ArrayData,$transpA);
		return $MatrizCov;
	}
	



}
?>
