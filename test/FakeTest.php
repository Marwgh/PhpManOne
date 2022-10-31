<?php



require_once 'classes/fake.php';

use PHPUnit\Framework\TestCase;

class FakeTest extends TestCase {
  /**
  * @dataProvider provideCPR
  */
  public function testCpr($value, $expected): void {
  $person = new fake;
  $res = $person->setCpr();
  $res = $person->checkCpr($value);

  $this->assertEquals($expected, $res);
  }
  public function provideCpr() {
      return [
          ['1234567890', true],   // Valid upper and lower boundary
          ['0000000000', true],
          ['9999999999', true],
          [1234567890, true],     
          ['99999999999', false], 
          ['999999999', false],   
          [12345678901, false],   
          [123456789, false],     
          ['ABCDEFGHIJ', false],
          
      ];
  }

  /**
  * @dataProvider provideDATE
  */
  public function testdate($value, $expected): void {
    $person = new fake;
    $res = $person->setFullNameGenderDate();
    $res = $person->checkDate($value);
  
    $this->assertEquals($expected, $res);
    }
    public function providedate() {
        return [
            ['1234567890', false],   // Valid upper and lower boundary
            ['10-10-10', false],
            [10-10-10, false],
            ["-1-10-10", false],
            ["32-13-99", false],
            ["30-12-1999", true],
            ["03-12-2029", true],
            ["3-12-2029", false],
        ];
    }


}