<?php


namespace App\Services;


use App\InputData\Strategies\InputFileInterface;
use App\InputData\InputStrategiesCollection;
use App\OutputData\Strategies\OutputFileInterface;
use App\OutputData\OutputStrategySelector;
use App\System\File\Files;

class FileService
{
    private string $input_folder_path;
    private string $output_folder_path;

    public function __construct(string $input_folder_path, string $output_folder_path)
    {
        $this->input_folder_path  = $input_folder_path;
        $this->output_folder_path = $output_folder_path;
    }


    public function getAllFilenamesFromInputFolder(): array
    {
        return Files::all($this->input_folder_path);
    }

    public function getAllFilenamesFromOutputFolder(): array
    {
        return Files::all($this->output_folder_path);
    }

    public function getFilenameForOutput()
    {
        $file_names = $this->getAllFilenamesFromOutputFolder();
        return $file_names[0] ?? null;
    }

    /**
     * @return OutputFileInterface[]
     */
    public function getInputFileProcessingStrategiesCollection(array $file_names): array
    {
        foreach ($file_names as $file_name) {

            $info = Files::info($this->input_folder_path . $file_name);
            $ext  = $info['extension'];
            if ($ext == 'xml') continue;

            $strategy = \App\InputData\InputStrategySelector::detectStrategy($ext);
            $strategy->setPath($this->input_folder_path . $file_name);

            InputStrategiesCollection::add($strategy);
        }
        return InputStrategiesCollection::getAll();
    }

    public function getOutputFileProcessingStrategy(string $file_name): OutputFileInterface
    {

        $info = Files::info($this->output_folder_path . $file_name);
        $ext  = $info['extension'];

        $strategy = OutputStrategySelector::detectStrategy($ext);
        $strategy->setPath($this->output_folder_path . $file_name);

        return $strategy;
    }
}