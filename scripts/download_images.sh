#!/usr/bin/env bash
# Downloads royalty-free sample images (Unsplash License: free for commercial use, no attribution required)
# into public/assets/seed/raw/. Replace with real project photos through the admin panel.
set -u
DIR="$(cd "$(dirname "$0")/.." && pwd)/public/assets/seed/raw"
mkdir -p "$DIR"
IDS=(
  1556911220-bff31c812dba 1556912172-45b7abe8b7e1 1558997519-83ea9252edf8
  1565538810643-b5bdb714032a 1484154218962-a197022b5858 1588854337115-1c67d9247e4d
  1600607687939-ce8a6c25118c 1600566753086-00f18fb6b3ea 1600210492486-724fe5c67fb0 1595428774223-ef52624120d2
  1616594039964-ae9021a400a0 1616486338812-3dadae4b4ace 1631679706909-1844bbd07221 1615873968403-89e068629265
  1620626011761-996317b8d101 1595526114035-0d45ed16cfbf 1556228453-efd6c1ff04f6
  1600121848594-d8644e57abab 1600489000022-c2086d79f9d4
)
n=0
for id in "${IDS[@]}"; do
  out="$DIR/$id.jpg"
  [ -s "$out" ] && { n=$((n+1)); continue; }
  if curl -sfL "https://images.unsplash.com/photo-$id?w=1800&q=80&fm=jpg" -o "$out" && file "$out" | grep -q JPEG; then
    n=$((n+1)); echo "ok  $id"
  else
    rm -f "$out"; echo "skip $id"
  fi
done
echo "$n images in $DIR"
