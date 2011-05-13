<?
	/**
	 *	������� ���������� ����� � ���������� �������������� ����������,
	 *	� ����������� �� ����������� ����������
	 *
	 * 	@param int $n
	 * 	@param string $oneending		-	����� ����� � ������������ �����
	 * 	@param string $twoending		-	����� ����� ��� ����������, �������� ����
	 * 	@param string $moreending		-	����� ����� ��� �������������� �����
	 * 	@param string $lang = "RU_ru"	-	Locale
	 * 
	 */
    function humanForm($n, $oneending, $twoending, $moreending = "", $lang = "RU_ru"){
        switch ($lang) {
        	case 'RU_ru':
        		$c = ($n % 10 == 1 && $n % 100 != 11 ? 0 : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 or $n % 100 >= 20) ? 1 : 2));
        	break;
        	
        	default:
        	break;
        }
        
        switch ($c){
            case 0: default:
                return $oneending;
            break;
            
            case 1:
                return $twoending;
            break;
            
            case 2:
                return $moreending;
            break;
        }
    }	
?>