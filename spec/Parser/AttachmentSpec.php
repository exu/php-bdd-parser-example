<?php

namespace spec\Parser;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AttachmentSpec extends ObjectBehavior
{
    function let()
    {
        $file = __DIR__ . '/../../example_attachments.txt';
        $this->beConstructedWith($file);
    }


    function it_is_initializable()
    {
        $this->shouldHaveType('Parser\Attachment');
    }

    function it_should_detect_id_in_header_line()
    {
        $header = "Attachment id: 6777";
        $this->getId($header)->shouldBe("6777");
    }

    function it_should_return_false_when_no_header_detected()
    {
        $header = "Some other Attachment id which is not header: 93894839";
        $this->getId($header)->shouldBe(false);
    }

    function it_should_collect_content_hashes_and_id_pairs()
    {
        $this->parseLine("Attachment id: 1\n");
        $this->parseLine("fsjdhf483493h934hfs\n");
        $this->parseLine("sfwqus483493u934usf\n");
        $this->parseLine("Attachment id: 2\n");
        $this->parseLine("fsjdhf483493h934hfs\n");
        $this->parseLine("sfwqus483493u934usf\n");
        $this->parseLine("Attachment id: 3\n");
        $this->parseLine("aasksdshfksjdhfkhds\n");
        $this->parseLine("Attachment id: 4\n");
        $this->parseLine("sfwqus483493u934usf\n");
        $this->reattachRest();


        $this->get()->shouldBeLike(
            [
                '0d62408156cc847be646ad6fc4b83b79' => [1, 2],
                '2fb79289a0be5f60be67d4c7308ddfdc' => [3],
                '9a1eec50f9a2608e898df6a472f9a24e' => [4],
            ]
        );
    }

    function it_should_parse_input_file()
    {
        $file = __DIR__ . '/../../example_attachments.txt';
        `echo "Attachment id: 1" > $file`;
        `echo "fsjdhf483493h934hfs" >> $file`;
        `echo "sfwqus483493u934usf" >> $file`;
        `echo "Attachment id: 2" >> $file`;
        `echo "fsjdhf483493h934hfs" >> $file`;
        `echo "sfwqus483493u934usf" >> $file`;
        `echo "Attachment id: 3" >> $file`;
        `echo "aasksdshfksjdhfkhds" >> $file`;
        `echo "Attachment id: 4" >> $file`;
        `echo "sfwqus483493u934usf" >> $file`;

        $this->parse();

        $this->get()->shouldBeLike(
            [
                '0d62408156cc847be646ad6fc4b83b79' => [1, 2],
                '2fb79289a0be5f60be67d4c7308ddfdc' => [3],
                '9a1eec50f9a2608e898df6a472f9a24e' => [4],
            ]
        );
    }
}
