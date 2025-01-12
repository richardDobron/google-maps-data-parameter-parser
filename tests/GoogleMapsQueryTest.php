<?php


use dobron\GoogleMapsQueryArgs;

class GoogleMapsQueryTest extends \PHPUnit\Framework\TestCase
{
    public static function queryProvider(): array
    {
        return [
            ['!1m8!4m7!1m2!1u260!2u160!2u11!3m2!1x462031492!2x145306029!2m2!1e2!2sspotlit!2m3!1e0!2sm!3i999999!3m3!2ssk!3sSK!5e1105!4e0!5m1!5f2.0!27m25!299174093m24!14m23!1m16!1m2!1y5150651941534423193!2y17243447519427663811!2zL20vMDNjbDkx!4m2!1x462052815!2x145391653!8b1!12m6!1m2!1x461945788!2x145138101!2m2!1x462117197!2x145473957!15sgcid:locality!2b0!3b1!4b0!5e0!6b0!8b0'],
            ['!1m5!1m4!1i12!2i2267!3i1403!4i256!2m3!1e0!2sm!3i675418132!2m3!1e2!2stravel-map!5i1!3m12!2scs-SK!3sSK!5e78!12m4!1e68!2m2!1sset!2sTravelDark!12m3!1e37!2m1!1ssmartmaps!4e0!5m1!5f2!23i1302826!23i47029525!23i47035313!23i47060239!23i10208620!26m1!1e3!27m18!399996237m17!17e10!24m11!1e3!4m2!5b1!12b1!5m3!1m1!2y16835832041953257994!3b1!9b1!21m1!1b1!28m3!1b0!2f86400!3i10208620'],
            ['!3m1!4b1!4m13!4m12!1m5!1m1!1s0x47bee19f7ccbda49:0x86dbf8c6685c9617!2m2!1d7.0982068!2d50.73743!1m5!1m1!1s0x47a84e373f035901:0x42120465b5e3b70!2m2!1d13.404954!2d52.5200066'],
        ];
    }

    /**
     * @dataProvider queryProvider
     */
    public function testParserAndBuilder(string $protocolBuffer)
    {
        $data = GoogleMapsQueryArgs::decode($protocolBuffer);

        $this->assertSame($protocolBuffer, GoogleMapsQueryArgs::encode($data));
    }

    public function testInvalidProtocolBuffer()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown param format: 1r6');

        GoogleMapsQueryArgs::decode('!1r6');
    }
}
