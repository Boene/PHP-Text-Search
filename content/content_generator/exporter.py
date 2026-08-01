import json
from pathlib import Path

def export_json(courses, output_directory, filename):
    path = Path(output_directory)
    path.mkdir(parents=True, exist_ok=True)

    with (path / filename).open("w", encoding="utf-8") as f:
        json.dump(courses, f, ensure_ascii=False, indent=2)